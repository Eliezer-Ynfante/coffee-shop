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

    public function test_guest_cannot_access_admin_routes(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_routes(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('customer.orders'));
    }

    public function test_admin_cannot_access_customer_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('customer.orders'))
            ->assertRedirect(route('admin.dashboard'));
    }
}