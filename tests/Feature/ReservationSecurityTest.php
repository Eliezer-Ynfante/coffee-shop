<?php

namespace Tests\Feature;

use App\Models\CafeTable;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\ReservationStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReservationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_is_saved_pending_and_returns_its_database_id(): void
    {
        CafeTable::create([
            'code' => 'S1',
            'zone' => 'salon',
            'zone_name' => 'Salón Principal',
            'name' => 'Mesa S1',
            'capacity' => 4,
            'status' => 'disponible',
            'is_active' => true,
        ]);

        $response = $this->postJson(route('reserva.store'), $this->reservationData());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', 1);

        $this->assertDatabaseHas('reservations', [
            'id' => 1,
            'mesa_id' => 'S1',
            'cafe_table_id' => 1,
            'zone_code' => 'salon',
            'zona' => 'Salón Principal',
            'status' => 'pending',
        ]);
    }

    public function test_reservation_is_rejected_when_its_time_overlaps_an_existing_booking(): void
    {
        CafeTable::create([
            'code' => 'S1',
            'zone' => 'salon',
            'zone_name' => 'Salón Principal',
            'name' => 'Mesa S1',
            'capacity' => 4,
            'status' => 'disponible',
            'is_active' => true,
        ]);

        Reservation::create([
            'nombre' => 'Reserva previa',
            'telefono' => '999111222',
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '04:30 PM',
            'personas' => 2,
            'mesa_id' => 'S1',
            'status' => 'confirmed',
        ]);

        $this->postJson(route('reserva.store'), $this->reservationData())
            ->assertUnprocessable()
            ->assertJsonValidationErrors('mesa_id');

        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_zone_reservation_uses_its_full_capacity_without_assigning_a_table(): void
    {
        foreach (['S1', 'S2'] as $code) {
            CafeTable::create([
                'code' => $code,
                'zone' => 'salon',
                'zone_name' => 'Salón Principal',
                'name' => "Mesa {$code}",
                'capacity' => 4,
                'status' => 'disponible',
                'is_active' => true,
            ]);
        }

        $this->postJson(route('reserva.store'), array_merge($this->reservationData(), [
            'tipo_reserva' => 'zona',
            'mesa_id' => null,
            'zona_id' => 'salon',
            'personas' => 8,
        ]))->assertOk()->assertJsonPath('success', true);

        $this->assertDatabaseHas('reservations', [
            'mesa_id' => null,
            'cafe_table_id' => null,
            'zone_code' => 'salon',
            'zona' => 'Salón Principal',
            'personas' => 8,
        ]);
    }

    public function test_zone_reservation_rejects_a_group_below_the_zone_capacity(): void
    {
        CafeTable::create([
            'code' => 'S1',
            'zone' => 'salon',
            'zone_name' => 'Salón Principal',
            'name' => 'Mesa S1',
            'capacity' => 4,
            'status' => 'disponible',
            'is_active' => true,
        ]);

        $this->postJson(route('reserva.store'), array_merge($this->reservationData(), [
            'tipo_reserva' => 'zona',
            'mesa_id' => null,
            'zona_id' => 'salon',
            'personas' => 3,
        ]))->assertUnprocessable()->assertJsonValidationErrors('personas');
    }

    public function test_zone_reservation_blocks_table_reservations_in_the_same_zone(): void
    {
        CafeTable::create([
            'code' => 'S1',
            'zone' => 'salon',
            'zone_name' => 'Salón Principal',
            'name' => 'Mesa S1',
            'capacity' => 4,
            'status' => 'disponible',
            'is_active' => true,
        ]);

        Reservation::create([
            'nombre' => 'Reserva por zona',
            'telefono' => '999111222',
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '04:30 PM',
            'personas' => 4,
            'mesa_id' => null,
            'zona' => 'Salón Principal',
            'status' => 'confirmed',
        ]);

        $this->postJson(route('reserva.store'), $this->reservationData())
            ->assertUnprocessable()
            ->assertJsonValidationErrors('mesa_id');
    }

    public function test_admin_rejects_image_and_social_urls_outside_the_allowlist(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->postJson(route('admin.gallery.store'), [
            'title' => 'Imagen no confiable',
            'category' => 'cafe',
            'image_url' => 'https://attacker.example/image.jpg',
        ])->assertUnprocessable()->assertJsonValidationErrors('image_url');

        $this->postJson(route('admin.settings.update'), [
            'redes' => ['instagram' => 'https://attacker.example/profile'],
        ])->assertUnprocessable()->assertJsonValidationErrors('redes.instagram');
    }

    /**
     * Sprint 0 — P8: El voucher (respuesta JSON) debe contener los datos reales
     * persistidos en la base de datos, no los del formulario.
     */
    public function test_json_response_contains_persisted_reservation_data(): void
    {
        CafeTable::create([
            'code'      => 'S1',
            'zone'      => 'salon',
            'zone_name' => 'Salón Principal',
            'name'      => 'Mesa S1',
            'capacity'  => 4,
            'status'    => 'disponible',
            'is_active' => true,
        ]);

        $response = $this->postJson(route('reserva.store'), $this->reservationData());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.mesa_id', 'S1')
            ->assertJsonPath('data.zona', 'Salón Principal')
            ->assertJsonPath('data.personas', 2)
            ->assertJsonPath('data.status', 'pending');

        // El código del voucher debe referirse al ID real persistido
        $reservation = \App\Models\Reservation::first();
        $response->assertJsonPath('data.id', $reservation->id);
        $this->assertStringContainsString((string) $reservation->id, $response->json('data.code'));
    }

    /**
     * Sprint 0 — P4: Una mesa con status='ocupada' (ocupada ahora mismo en el local)
     * puede reservarse para una fecha futura si no hay solapamiento de reservas.
     * La disponibilidad para fechas futuras la determina hasTimeConflict, no el status global.
     */
    public function test_table_with_global_occupied_status_can_be_reserved_for_future_date(): void
    {
        CafeTable::create([
            'code'      => 'S1',
            'zone'      => 'salon',
            'zone_name' => 'Salón Principal',
            'name'      => 'Mesa S1',
            'capacity'  => 4,
            'status'    => 'ocupada', // ocupada ahora, pero sin reservas en la fecha solicitada
            'is_active' => true,
        ]);

        $response = $this->postJson(route('reserva.store'), $this->reservationData());

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('reservations', [
            'mesa_id' => 'S1',
            'status'  => 'pending',
        ]);
    }

    /**
     * Sprint 0 — P1: Enviar mesa_id vacío con tipo_reserva=mesa debe ser rechazado
     * con error de validación, sin crear ninguna reserva.
     */
    public function test_empty_mesa_id_is_rejected_for_table_reservation(): void
    {
        $response = $this->postJson(route('reserva.store'), array_merge($this->reservationData(), [
            'tipo_reserva' => 'mesa',
            'mesa_id'      => '',
        ]));

        $response->assertUnprocessable()->assertJsonValidationErrors('mesa_id');
        $this->assertDatabaseCount('reservations', 0);
    }

    /**
     * Sprint 0 — P8: Dos solicitudes concurrentes para la misma mesa, fecha y hora
     * solo deben crear una reserva. La segunda debe recibir un error de validación.
     *
     * Nota: En SQLite (testing) los bloqueos FOR UPDATE son simulados; este test
     * verifica que la lógica de hasTimeConflict detecta el solapamiento correctamente
     * cuando la primera reserva ya fue persistida antes de que llegue la segunda.
     */
    public function test_concurrent_requests_do_not_double_book_same_table(): void
    {
        CafeTable::create([
            'code'      => 'S1',
            'zone'      => 'salon',
            'zone_name' => 'Salón Principal',
            'name'      => 'Mesa S1',
            'capacity'  => 4,
            'status'    => 'disponible',
            'is_active' => true,
        ]);

        // Primera solicitud: debe aceptarse
        $first = $this->postJson(route('reserva.store'), $this->reservationData());
        $first->assertOk()->assertJsonPath('success', true);

        // Segunda solicitud con misma mesa, fecha y hora: debe rechazarse
        $second = $this->postJson(route('reserva.store'), $this->reservationData());
        $second->assertUnprocessable()->assertJsonValidationErrors('mesa_id');

        // Solo debe existir una reserva en la base de datos
        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_public_map_uses_active_database_tables(): void
    {
        CafeTable::create([
            'code' => 'DB9',
            'zone' => 'salon-nuevo',
            'zone_name' => 'Salón Nuevo',
            'name' => 'Mesa Base de Datos',
            'capacity' => 3,
            'status' => 'disponible',
            'is_active' => true,
            'coord_x' => 42,
            'coord_y' => 56,
        ]);

        $this->get(route('reserva'))
            ->assertOk()
            ->assertSee('data-table-id="DB9"', false)
            ->assertSee('data-zone="salon-nuevo"', false)
            ->assertSee('Mesa Base de Datos');
    }

    public function test_availability_uses_the_same_table_and_zone_conflicts_as_booking(): void
    {
        Notification::fake();
        CafeTable::create([
            'code' => 'S1',
            'zone' => 'salon',
            'zone_name' => 'Salón Principal',
            'name' => 'Mesa S1',
            'capacity' => 4,
            'status' => 'disponible',
            'is_active' => true,
        ]);

        $this->postJson(route('reserva.store'), $this->reservationData())->assertOk();

        $this->getJson(route('reserva.availability', [
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '05:00 PM',
        ]))
            ->assertOk()
            ->assertJsonPath('tables.S1', false)
            ->assertJsonPath('zones.salon', false);

        $this->getJson(route('reserva.availability', [
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '07:30 PM',
        ]))->assertJsonPath('tables.S1', true);
    }

    public function test_admin_can_configure_reservation_schedule_and_slots_are_generated(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('admin.settings.update'), [
                'reservation_open_time' => '09:00',
                'reservation_close_time' => '12:00',
                'reservation_slot_interval_minutes' => 60,
                'reservation_slot_duration_minutes' => 90,
                'reservation_buffer_minutes' => 15,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('settings', ['key' => 'reservation_open_time', 'value' => '09:00']);

        $this->get(route('reserva'))
            ->assertOk()
            ->assertSee('data-time="09:00 AM"', false)
            ->assertSee('data-time="10:00 AM"', false)
            ->assertDontSee('data-time="11:00 AM"', false);

        $this->getJson(route('reserva.availability', [
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '11:00 AM',
        ]))->assertUnprocessable()->assertJsonValidationErrors('hora');
    }

    public function test_reservation_creation_and_admin_status_changes_notify_the_customer(): void
    {
        Notification::fake();
        CafeTable::create([
            'code' => 'S1',
            'zone' => 'salon',
            'zone_name' => 'Salón Principal',
            'name' => 'Mesa S1',
            'capacity' => 4,
            'status' => 'disponible',
            'is_active' => true,
        ]);

        $this->postJson(route('reserva.store'), $this->reservationData())->assertOk();
        Notification::assertSentOnDemand(ReservationStatusNotification::class);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->patch(route('admin.reservations.status', ['id' => 1]), ['status' => 'confirmed'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'confirmed']);
        $this->patch(route('admin.reservations.status', ['id' => 1]), ['status' => 'no_show'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'no_show']);
        Notification::assertSentOnDemand(ReservationStatusNotification::class, function (ReservationStatusNotification $notification, array $channels, $notifiable) {
            return in_array('mail', $channels, true) && $notifiable->routes['mail'] === 'cliente@example.com';
        });
    }

    private function reservationData(): array
    {
        return [
            'nombre'       => 'Cliente de prueba',
            'telefono'     => '999111222',
            'email'        => 'cliente@example.com',
            'fecha'        => now()->addDays(3)->toDateString(),
            'personas'     => 2,
            'tipo_reserva' => 'mesa',
            'hora'         => '05:00 PM',
            'mesa_id'      => 'S1',
        ];
    }
}
