<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Reservation;
use App\Notifications\ReservationStatusNotification;
use App\Services\ReservationSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{
    public function __construct(private readonly ReservationSchedule $schedule)
    {
    }

    public function index()
    {
        $tables = CafeTable::where('is_active', true)->orderBy('zone')->orderBy('code')->get();
        $zoneIndexes = [];
        $mesas3d = $tables->map(function (CafeTable $table) use (&$zoneIndexes) {
            $index = $zoneIndexes[$table->zone] ?? 0;
            $zoneIndexes[$table->zone] = $index + 1;
            [$defaultX, $defaultY] = $this->defaultCoordinates($table->zone, $index);

            return [
                'id' => $table->code,
                'zona' => $table->zone,
                'zona_nombre' => $table->zone_name ?: ucfirst($table->zone),
                'nombre' => $table->name,
                'capacidad' => $table->capacity,
                'estado' => $table->status,
                'x' => $table->coord_x ?: $defaultX,
                'y' => $table->coord_y ?: $defaultY,
                'icono' => $table->icon,
            ];
        })->values()->all();

        $zoneDetails = collect(config('cafe.zonas_reserva', []))->keyBy('id');
        $reservationZones = $tables->groupBy('zone')->map(function ($zoneTables, string $zone) use ($zoneDetails) {
            $details = $zoneDetails->get($zone, []);

            return array_merge($details, [
                'id' => $zone,
                'nombre' => $zoneTables->first()->zone_name ?: ($details['nombre'] ?? ucfirst($zone)),
                'capacidad' => min(20, (int) $zoneTables->sum('capacity')),
            ]);
        })->values()->all();

        $reservationSlots = $this->schedule->slots();

        return view('reserva', compact('mesas3d', 'reservationZones', 'reservationSlots'));
    }

    public function availability(Request $request)
    {
        $validated = $request->validate([
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'string', Rule::in($this->schedule->slots())],
        ]);

        $tables = CafeTable::where('is_active', true)->orderBy('zone')->orderBy('code')->get();
        $availability = $tables->mapWithKeys(function (CafeTable $table) use ($validated) {
            return [$table->code => $table->status !== 'mantenimiento'
                && ! $this->tableHasConflict($table, $validated['fecha'], $validated['hora'])];
        });

        $zones = $tables->groupBy('zone')->map(function ($zoneTables, $zoneCode) use ($validated) {
            $zoneName = $zoneTables->first()->zone_name ?: ucfirst($zoneCode);

            return ! $zoneTables->contains(fn (CafeTable $table) => $table->status === 'mantenimiento')
                && ! $this->zoneHasConflict($zoneCode, $zoneName, $zoneTables, $validated['fecha'], $validated['hora']);
        });

        return response()->json(['tables' => $availability, 'zones' => $zones]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:100',
            'telefono'     => 'required|string|max:30',
            'email'        => 'nullable|email|max:150',
            'fecha'        => 'required|date|after_or_equal:today',
            'personas'     => 'required|integer|min:1|max:20',
            'hora'         => ['required', 'string', Rule::in($this->schedule->slots())],
            'tipo_reserva' => ['required', Rule::in(['mesa', 'zona'])],
            'mesa_id'      => ['nullable', 'required_if:tipo_reserva,mesa', 'string', 'max:20', Rule::exists('cafe_tables', 'code')->where('is_active', true)],
            'zona_id'      => ['nullable', 'required_if:tipo_reserva,zona', 'string', Rule::in($this->reservationZoneIds())],
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

                if ($tables->contains(fn (CafeTable $table) => $table->status === 'mantenimiento')) {
                    throw ValidationException::withMessages([
                        'zona_id' => 'La zona contiene mesas en mantenimiento y no está disponible para reservarse completa.',
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
                $hasConflict = $this->zoneHasConflict(
                    $validated['zona_id'],
                    $zoneName,
                    $tables,
                    $validated['fecha'],
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
                    'cafe_table_id' => null,
                    'zona'        => $zoneName,
                    'zone_code'   => $validated['zona_id'],
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
                ->where('status', '!=', 'mantenimiento')
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

            $zoneName = $table->zone_name ?: $table->zone;
            $hasConflict = $this->tableHasConflict($table, $validated['fecha'], $validated['hora']);

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
                'cafe_table_id' => $table->id,
                'zona'        => $table->zone_name ?: $table->zone,
                'zone_code'   => $table->zone,
                'ocasion'     => $validated['ocasion'] ?? null,
                'comentarios' => $validated['notas'] ?? null,
                'status'      => 'pending',
            ]);
        });

        if ($reservation->email) {
            Notification::route('mail', $reservation->email)->notify(new ReservationStatusNotification($reservation->load('table')));
        }

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
                    'mesa_nombre' => $reservation->table?->name ?? $reservation->mesa_id,
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
        $duration = $this->schedule->durationMinutes() + $this->schedule->bufferMinutes();

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

    private function tableHasConflict(CafeTable $table, string $date, string $time): bool
    {
        return $this->hasTimeConflict(
            Reservation::query()->whereDate('fecha', $date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function (Builder $query) use ($table) {
                    $query->where('cafe_table_id', $table->id)
                        ->orWhere('mesa_id', $table->code)
                        ->orWhere(function (Builder $query) use ($table) {
                            $query->whereNull('cafe_table_id')
                                ->whereNull('mesa_id')
                                ->where(function (Builder $query) use ($table) {
                                    $query->where('zone_code', $table->zone)
                                        ->orWhere('zona', $table->zone_name ?: $table->zone)
                                        ->orWhere('zona', $table->zone);
                                });
                        });
                }),
            $time
        );
    }

    private function zoneHasConflict(string $zoneCode, string $zoneName, $tables, string $date, string $time): bool
    {
        return $this->hasTimeConflict(
            Reservation::query()->whereDate('fecha', $date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function (Builder $query) use ($zoneCode, $zoneName, $tables) {
                    $query->where('zone_code', $zoneCode)
                        ->orWhere('zona', $zoneName)
                        ->orWhereIn('cafe_table_id', $tables->pluck('id'))
                        ->orWhereIn('mesa_id', $tables->pluck('code'));
                }),
            $time
        );
    }

    private function defaultCoordinates(string $zone, int $index): array
    {
        return match ($zone) {
            'barra' => [8 + ($index % 4) * 10, 20],
            'salon' => [8 + ($index % 4) * 12, 45 + intdiv($index, 4) * 20],
            'terraza' => [60 + ($index % 2) * 18, 18 + intdiv($index, 2) * 18],
            default => [60 + ($index % 4) * 9, 72 + intdiv($index, 4) * 14],
        };
    }

    private function reservationZoneIds(): array
    {
        if (! Schema::hasTable('cafe_tables')) {
            return array_column(config('cafe.zonas_reserva', []), 'id');
        }

        return CafeTable::where('is_active', true)->distinct()->pluck('zone')->all();
    }
}
