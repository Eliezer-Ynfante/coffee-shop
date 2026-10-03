<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsFormRequest;
use App\Models\Setting;

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
            'hero_img', 'about_img',
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $validated)) {
                Setting::set($key, $validated[$key], 'general');
            }
        }

        if (array_key_exists('redes', $validated)) {
            Setting::set('redes', $validated['redes'], 'contacto', 'json');
        }

        return back()->with('status', 'Ajustes de la cafetería actualizados correctamente.');
    }
}
