<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsFormRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(SettingsFormRequest $request)
    {
        $validated = $request->validated();
        $keys = [
            'nombre', 'slogan', 'titulo', 'subtitulo', 'descripcion',
            'subtag', 'horario', 'direccion', 'email', 'telefono',
            'yape_phone', 'hero_img', 'about_img',
            'reservation_open_time', 'reservation_close_time',
            'reservation_slot_interval_minutes', 'reservation_slot_duration_minutes',
            'reservation_buffer_minutes',
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $validated)) {
                Setting::set($key, $validated[$key], 'general');
            }
        }

        if (array_key_exists('redes', $validated)) {
            Setting::set('redes', $validated['redes'], 'contacto', 'json');
        }

        if ($request->hasFile('payment_qr')) {
            $qrPath = $request->file('payment_qr')->store('payment-qr', 'public');
            Setting::set('payment_qr', $qrPath, 'payments', 'image');
        }

        return back()->with('status', 'Ajustes de la cafetería actualizados correctamente.');
    }
}
