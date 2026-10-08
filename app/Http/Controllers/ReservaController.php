<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{
    /**
     * Devuelve la duración de cada slot de reserva en minutos.
     * Lee de config/cafe.php (clave 'slot_duration_minutes'); si no existe, usa 90.
     * Sprint 0 — P5: duración configurable sin tocar código.
     */
    private function slotDurationMinutes(): int
    {
        return max(30, (int) config('cafe.slot_duration_minutes', 90));
    }

    public function index()
    {
        $dbMesas = CafeTable::where('is_active', true)->get();

        if ($dbMesas->isNotEmpty()) {
            $mesas3d = $dbMesas->map(function ($m) {
                return [
                    'id'       => $m->code,
                    'zona'     => $m->zone,
                    'nombre'   => $m->name,
                    'capacidad'=> $m->capacity,
                    'estado'   => $m->status,
                    'x'        => $m->coord_x,
                    'y'        => $m->coord_y,
                    'icono'    => $m->icon,
                ];
            })->toArray();
        } else {
            $mesas3d = config('cafe.mesas_3d', []);
        }

        $capacidadesMesas = array_column($mesas3d, 'capacidad', 'id');

        return view('reserva', compact('mesas3d', 'capacidadesMesas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:100',
            'telefono'     => 'required|string|max:30',
            'email'        => 'nullable|email|max:150',
            'fecha'        => 'required|date|after_or_equal:today',
            'personas'     => 'required|integer|min:1|max:20',
            'hora'         => ['required', 'string', Rule::in(config('cafe.turnos_horarios', []))],
            'tipo_reserva' => ['required', Rule::in(['mesa', 'zona'])],
            'mesa_id'      => ['nullable', 'required_if:tipo_reserva,mesa', 'string', 'max:20', Rule::exists('cafe_tables', 'code')->where('is_active', true)],
            'zona_id'      => ['nullable', 'required_if:tipo_reserva,zona', 'string', Rule::in(array_column(config('cafe.zonas_reserva', []), 'id'))],
            'ocasion'      => 'nullable|string|max:50',
            'notas'        => 'nullable|string|max:1000',
        ]);

        $reservation = DB::transaction(function () use ($validated) {

            /* ----------------------------------------------------------------
             * RAMA: Reserva de ZONA COMPLETA
             * ---------------------------------------------------------------- */
            if ($validated['tipo_reserva'] === 'zona') {
                $tables = CafeTable::where('zone', $validated['zona_id'])
                    ->where('is_active', true)
                    ->orderBy('code')
                    ->lockForUpdate()
                    ->get();

                if ($tables->isEmpty()) {
                    throw ValidationException::withMessages([
                        'zona_id' => 'La zona seleccionada no tiene mesas habilitadas.',
                    ]);
                }

                $capacity = min(20, (int) $tables->sum('capacity'));
                if ((int) $validated['personas'] !== $capacity) {
                    throw ValidationException::withMessages([
                        'personas' => 'Las reservas por zona deben usar la capacidad máxima disponible (' . $capacity . ' personas).',
                    ]);
                }

                $zoneConfig = collect(config('cafe.zonas_reserva', []))->firstWhere('id', $validated['zona_id']);
                $zoneName   = $tables->first()->zone_name ?: ($zoneConfig['nombre'] ?? $validated['zona_id']);

                // Sprint 0 — P4: verificar solapamiento por fecha+hora, no por status global.
                $hasConflict = $this->hasTimeConflict(
                    Reservation::whereDate('fecha', $validated['fecha'])
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->where(function (Builder $query) use ($zoneName, $tables) {
                            $query->where('zona', $zoneName)
                                ->orWhereIn('mesa_id', $tables->pluck('code'));
                        }),
                    $validated['hora']
                );

                if ($hasConflict) {
                    throw ValidationException::withMessages([
                        'zona_id' => 'La zona ya tiene una reserva para la fecha y el turno seleccionados.',
                    ]);
                }

                return Reservation::create([
                    'nombre'      => $validated['nombre'],
                    'telefono'    => $validated['telefono'],
                    'email'       => $validated['email'] ?? null,
                    'fecha'       => $validated['fecha'],
                    'hora'        => $validated['hora'],
                    'personas'    => $validated['personas'],
                    'mesa_id'     => null,
                    'zona'        => $zoneName,
                    'ocasion'     => $validated['ocasion'] ?? null,
                    'comentarios' => $validated['notas'] ?? null,
                    'status'      => 'pending',
                ]);
            }

            /* ----------------------------------------------------------------
             * RAMA: Reserva de MESA INDIVIDUAL
             * ---------------------------------------------------------------- */
            $table = CafeTable::where('code', $validated['mesa_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            // Sprint 0 — P4: rechazamos solo si la mesa no existe o no está activa.
            // El campo status ('disponible'/'ocupada') refleja ocupación actual del local,
            // no disponibilidad futura. La verificación de solapamiento temporal es la
            // fuente de verdad correcta para reservas en fechas futuras.
            if (! $table) {
                throw ValidationException::withMessages([
                    'mesa_id' => 'La mesa seleccionada no existe o no está habilitada.',
                ]);
            }

            if ($validated['personas'] > $table->capacity) {
                throw ValidationException::withMessages([
                    'personas' => 'La mesa seleccionada no tiene capacidad para ese número de personas (máx. ' . $table->capacity . ').',
                ]);
            }

            $zoneName    = $table->zone_name ?: $table->zone;
            $hasConflict = $this->hasTimeConflict(
                Reservation::whereDate('fecha', $validated['fecha'])
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function (Builder $query) use ($table, $zoneName) {
                        $query->where('mesa_id', $table->code)
                            ->orWhere(function (Builder $query) use ($zoneName) {
                                // Una reserva de zona completa bloquea esta mesa en el mismo intervalo.
                                $query->whereNull('mesa_id')->where('zona', $zoneName);
                            });
                    }),
                $validated['hora']
            );

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'mesa_id' => 'Esa mesa ya tiene una reserva para la fecha y el turno seleccionados.',
                ]);
            }

            return Reservation::create([
                'nombre'      => $validated['nombre'],
                'telefono'    => $validated['telefono'],
                'email'       => $validated['email'] ?? null,
                'fecha'       => $validated['fecha'],
                'hora'        => $validated['hora'],
                'personas'    => $validated['personas'],
                'mesa_id'     => $table->code,
                'zona'        => $table->zone_name ?: $table->zone,
                'ocasion'     => $validated['ocasion'] ?? null,
                'comentarios' => $validated['notas'] ?? null,
                'status'      => 'pending',
            ]);
        });

        // Sprint 0 — P2: la respuesta incluye todos los datos persistidos.
        // El frontend construye el voucher exclusivamente desde esta respuesta,
        // nunca desde los campos ocultos del formulario.
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tu solicitud de reserva fue recibida y está pendiente de confirmación.',
                'data'    => [
                    'id'          => $reservation->id,
                    'code'        => 'RG-' . str_pad($reservation->id, 4, '0', STR_PAD_LEFT),
                    'mesa_id'     => $reservation->mesa_id,
                    'mesa_nombre' => $reservation->mesa_id
                        ? (CafeTable::where('code', $reservation->mesa_id)->value('name') ?? $reservation->mesa_id)
                        : null,
                    'zona'        => $reservation->zona,
                    'fecha'       => $reservation->fecha->format('d/m/Y'),
                    'hora'        => $reservation->hora,
                    'personas'    => $reservation->personas,
                    'nombre'      => $reservation->nombre,
                    'status'      => $reservation->status, // siempre 'pending' en este punto
                ],
            ]);
        }

        return back()->with('success', 'Tu solicitud de reserva fue recibida y está pendiente de confirmación.');
    }

    /**
     * Detecta solapamiento de tiempo entre la solicitud y reservas existentes.
     *
     * Sprint 0 — P5: usa slotDurationMinutes() en lugar de la constante fija.
     *
     * Dos intervalos [A, A+dur) y [B, B+dur) se solapan cuando A < B+dur && A+dur > B.
     */
    private function hasTimeConflict(Builder $reservations, string $requestedTime): bool
    {
        $duration = $this->slotDurationMinutes();

        $requestedStart       = Carbon::parse($requestedTime);
        $requestedStartMinute = ($requestedStart->hour * 60) + $requestedStart->minute;
        $requestedEndMinute   = $requestedStartMinute + $duration;

        return $reservations->get(['hora'])->contains(function (Reservation $existing) use ($requestedStartMinute, $requestedEndMinute, $duration) {
            $existingTime = $existing->getAttribute('hora');
            if (! is_string($existingTime) || $existingTime === '') {
                return false;
            }

            $existingStart       = Carbon::parse($existingTime);
            $existingStartMinute = ($existingStart->hour * 60) + $existingStart->minute;
            $existingEndMinute   = $existingStartMinute + $duration;

            return $requestedStartMinute < $existingEndMinute && $requestedEndMinute > $existingStartMinute;
        });
    }
}
