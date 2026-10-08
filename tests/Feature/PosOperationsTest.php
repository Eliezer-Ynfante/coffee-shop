<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosOperationsTest extends TestCase
{
    use RefreshDatabase;

    private function createPosProduct(): Product
    {
        $category = Category::create([
            'name' => 'Café caliente',
            'slug' => 'cafe-caliente',
            'is_active' => true,
        ]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Espresso Americano',
            'slug' => 'espresso-americano',
            'price' => 12.00,
            'cost_price' => 4.00,
            'stock' => 50,
            'available_in_pos' => true,
            'available_in_store' => true,
            'is_active' => true,
            'preparation_time' => 4,
        ]);
    }

    public function test_admin_can_create_pos_order_from_quick_sale(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->createPosProduct();
        $this->actingAs($admin);

        $response = $this->post(route('admin.pos.store'), [
            'customer_name' => 'Cliente Mesa',
            'payment_method' => 'cash',
            'payment_reference' => 'CAJA-1001',
            'items' => [[
                'product_id' => $product->id,
                'quantity' => 2,
            ]],
        ]);

        $response->assertRedirect(route('admin.pos.index'));

        $this->assertDatabaseHas('orders', [
            'channel' => 'pos',
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'customer_name' => 'Cliente Mesa',
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 12.00,
            'subtotal' => 24.00,
        ]);
    }

    public function test_barista_queue_shows_orders_in_preparation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Order::create([
            'order_number' => 'POS-QUEUE-01',
            'channel' => 'pos',
            'status' => 'preparing',
            'subtotal' => 12.00,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total' => 12.00,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'customer_name' => 'Cliente cola',
            'customer_phone' => null,
            'notes' => 'Mesa 2',
        ]);

        $response = $this->get(route('admin.barista.index'));

        $response->assertOk();
        $response->assertSee('POS-QUEUE-01');
    }
}
