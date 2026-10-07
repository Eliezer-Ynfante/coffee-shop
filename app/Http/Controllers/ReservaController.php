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
    private const SLOT_DURATION_MINUTES = 90;

    public function index()
    {
        $dbMesas = CafeTable::where('is_active', true)->get();

        if ($dbMesas->isNotEmpty()) {
            $mesas3d = $dbMesas->map(function ($m) {
                return [
                    'id' => $m->code,
                    'zona' => $m->zone,
                    'nombre' => $m->name,
                    'capacidad' => $m->capacity,
                    'estado' => $m->status,
                    'x' => $m->coord_x,
                    'y' => $m->coord_y,
                    'icono' => $m->icon,
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
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'fecha' => 'required|date|after_or_equal:today',
            'personas' => 'required|integer|min:1|max:20',
            'hora' => ['required', 'string', Rule::in(config('cafe.turnos_horarios', []))],
            'tipo_reserva' => ['required', Rule::in(['mesa', 'zona'])],
            'mesa_id' => ['nullable', 'required_if:tipo_reserva,mesa', 'string', 'max:20', Rule::exists('cafe_tables', 'code')->where('is_active', true)],
            'zona_id' => ['nullable', 'required_if:tipo_reserva,zona', 'string', Rule::in(array_column(config('cafe.zonas_reserva', []), 'id'))],
            'ocasion' => 'nullable|string|max:50',
            'notas' => 'nullable|string|max:1000',
        ]);

        $reservation = DB::transaction(function () use ($validated) {
            if ($validated['tipo_reserva'] === 'zona') {
                $tables = CafeTable::where('zone', $validated['zona_id'])
                    ->where('is_active', true)
                    ->where('status', 'disponible')
                    ->orderBy('code')
                    ->lockForUpdate()
                    ->get();

                if ($tables->isEmpty()) {
                    throw ValidationException::withMessages([
                        'zona_id' => 'La zona seleccionada ya no tiene espacios disponibles.',
                    ]);
                }

                $capacity = min(20, (int) $tables->sum('capacity'));
                if ($validated['personas'] !== $capacity) {
                    throw ValidationException::withMessages([
                        'personas' => 'Las reservas por zona deben usar la capacidad máxima disponible.',
                    ]);
                }

                $zoneConfig = collect(config('cafe.zonas_reserva', []))->firstWhere('id', $validated['zona_id']);
                $zoneName = $tables->first()->zone_name ?: ($zoneConfig['nombre'] ?? $validated['zona_id']);
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
                    'nombre' => $validated['nombre'],
                    'telefono' => $validated['telefono'],
                    'email' => $validated['email'] ?? null,
                    'fecha' => $validated['fecha'],
                    'hora' => $validated['hora'],
                    'personas' => $validated['personas'],
                    'mesa_id' => null,
                    'zona' => $zoneName,
                    'ocasion' => $validated['ocasion'] ?? null,
                    'comentarios' => $validated['notas'] ?? null,
                    'status' => 'pending',
                ]);
            }

            $table = CafeTable::where('code', $validated['mesa_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (! $table || $table->status !== 'disponible') {
                throw ValidationException::withMessages([
                    'mesa_id' => 'La mesa seleccionada ya no está disponible.',
                ]);
            }

            if ($validated['personas'] > $table->capacity) {
                throw ValidationException::withMessages([
                    'personas' => 'La mesa seleccionada no tiene capacidad para ese grupo.',
                ]);
            }

            $zoneName = $table->zone_name ?: $table->zone;
            $hasConflict = $this->hasTimeConflict(
                Reservation::whereDate('fecha', $validated['fecha'])
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function (Builder $query) use ($table, $zoneName) {
                        $query->where('mesa_id', $table->code)
                            ->orWhere(function (Builder $query) use ($zoneName) {
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
                'nombre' => $validated['nombre'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'] ?? null,
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora'],
                'personas' => $validated['personas'],
                'mesa_id' => $table->code,
                'zona' => $table->zone_name ?: $table->zone,
                'ocasion' => $validated['ocasion'] ?? null,
                'comentarios' => $validated['notas'] ?? null,
                'status' => 'pending',
            ]);
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tu solicitud de reserva fue recibida y está pendiente de confirmación.',
                'data' => ['id' => $reservation->id],
            ]);
        }

        return back()->with('success', 'Tu solicitud de reserva fue recibida y está pendiente de confirmación.');
    }

    private function hasTimeConflict(Builder $reservations, string $requestedTime): bool
    {
        $requestedStart = Carbon::parse($requestedTime);
        $requestedStartMinute = ($requestedStart->hour * 60) + $requestedStart->minute;
        $requestedEndMinute = $requestedStartMinute + self::SLOT_DURATION_MINUTES;

        return $reservations->get(['hora'])->contains(function (Reservation $existing) use ($requestedStartMinute, $requestedEndMinute) {
            $existingTime = $existing->getAttribute('hora');
            if (! is_string($existingTime) || $existingTime === '') {
                return false;
            }

            $existingStart = Carbon::parse($existingTime);
            $existingStartMinute = ($existingStart->hour * 60) + $existingStart->minute;
            $existingEndMinute = $existingStartMinute + self::SLOT_DURATION_MINUTES;

            return $requestedStartMinute < $existingEndMinute && $requestedEndMinute > $existingStartMinute;
        });
    }
}
