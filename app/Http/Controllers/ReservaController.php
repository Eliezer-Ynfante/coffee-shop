<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        $dbMesas = \App\Models\CafeTable::where('is_active', true)->get();

        if ($dbMesas->isNotEmpty()) {
            $mesas3d = $dbMesas->map(function ($m) {
                return [
                    'id'        => $m->code,
                    'zona'      => $m->zone,
                    'nombre'    => $m->name,
                    'capacidad' => $m->capacity,
                    'estado'    => $m->status,
                    'x'         => $m->coord_x,
                    'y'         => $m->coord_y,
                    'icono'     => $m->icon,
                ];
            })->toArray();
        } else {
            $mesas3d = config('cafe.mesas_3d', []);
        }

        return view('reserva', compact('mesas3d'));
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

        $reservaId = \Illuminate\Support\Facades\DB::table('reservations')->insertGetId([
            'nombre'      => $validated['nombre'],
            'telefono'    => $validated['telefono'],
            'email'       => $validated['email'] ?? null,
            'fecha'       => $validated['fecha'],
            'hora'        => $validated['hora'] ?? '10:00',
            'personas'    => $validated['personas'],
            'mesa_id'     => $validated['mesa_id'] ?? null,
            'zona'        => $validated['zona'] ?? null,
            'ocasion'     => $validated['ocasion'] ?? null,
            'comentarios' => $validated['comentarios'] ?? $validated['nota'] ?? null,
            'status'      => 'confirmed',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Tu reserva ha sido confirmada con éxito! Te esperamos.',
                'data'    => array_merge($validated, ['id' => $reservaId])
            ]);
        }

        return back()->with('success', '¡Tu reserva ha sido confirmada con éxito! Te esperamos.');
    }
}
