<?php

namespace Tests\Feature;

use App\Models\CafeTable;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    private function reservationData(): array
    {
        return [
            'nombre' => 'Cliente de prueba',
            'telefono' => '999111222',
            'email' => 'cliente@example.com',
            'fecha' => now()->addDays(3)->toDateString(),
            'personas' => 2,
            'hora' => '05:00 PM',
            'mesa_id' => 'S1',
        ];
    }
}