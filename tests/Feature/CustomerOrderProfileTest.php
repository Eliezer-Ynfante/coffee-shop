<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_portal_creates_a_missing_profile_and_loads(): void
    {
        $user = User::factory()->create([
            'name' => 'Ana Torres',
            'role' => 'customer',
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders'))
            ->assertOk();

        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'first_name' => 'Ana',
            'last_name' => 'Torres',
        ]);
    }
}