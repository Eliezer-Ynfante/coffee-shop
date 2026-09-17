<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        return view('reserva');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'telefono'    => 'required|string|max:30',
            'email'       => 'nullable|email|max:150',
            'fecha'       => 'required|date',
            'personas'    => 'required|integer|min:1|max:20',
            'hora'        => 'nullable|string|max:20',
            'mesa_id'     => 'nullable|string|max:10',
            'zona'        => 'nullable|string|max:50',
            'ocasion'     => 'nullable|string|max:50',
            'comentarios' => 'nullable|string|max:1000',
            'nota'        => 'nullable|string|max:1000',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Tu reserva ha sido confirmada con éxito! Te esperamos.',
                'data'    => $validated
            ]);
        }

        return back()->with('success', '¡Tu reserva ha sido confirmada con éxito! Te esperamos.');
    }
}
