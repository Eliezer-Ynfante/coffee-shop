<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:100',
            'email'    => 'required|email|max:150',
            'telefono' => 'nullable|string|max:30',
            'motivo'   => 'required|string|max:50',
            'mensaje'  => 'required|string|max:2000',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado con éxito! Nos comunicaremos contigo a la brevedad.'
            ]);
        }

        return back()->with('success', '¡Mensaje enviado con éxito! Nos comunicaremos contigo a la brevedad.');
    }
}
