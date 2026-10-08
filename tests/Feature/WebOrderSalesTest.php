<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebOrderSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_successfully_from_web(): void
    {
        $category = Category::create([
            'name' => 'Café caliente',
            'slug' => 'cafe-caliente',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Espresso Americano',
            'slug' => 'espresso-americano',
            'price' => 8.00,
            'cost_price' => 3.00,
            'stock' => 50,
            'is_active' => true,
            'available_in_store' => true,
        ]);

        $payload = [
            'customer_name'  => 'Juan Pérez',
            'customer_phone' => '999888777',
            'delivery_type'  => 'mesa',
            'table_number'   => 'S1',
            'items' => [
                [
                    'name'     => 'Espresso Americano',
                    'quantity' => 2,
                ]
            ],
        ];

        $response = $this->postJson(route('pedido.store'), $payload);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('orders', [
            'customer_name'  => 'Juan Pérez',
            'customer_phone' => '999888777',
            'channel'        => 'ecommerce',
            'status'         => 'pending',
            'total'          => 16.00,
        ]);

        $order = Order::first();
        $this->assertDatabaseHas('order_items', [
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => 2,
            'unit_price' => 8.00,
            'subtotal'   => 16.00,
        ]);
    }

    public function test_server_calculates_price_ignoring_client_manipulation(): void
    {
        $category = Category::create([
            'name' => 'Postres',
            'slug' => 'postres',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Cheesecake',
            'slug' => 'cheesecake',
            'price' => 14.00,
            'cost_price' => 5.00,
            'stock' => 20,
            'is_active' => true,
            'available_in_store' => true,
        ]);

        // Intento de manipulación de precio en el cliente
        $payload = [
            'customer_name'  => 'Carlos',
            'customer_phone' => '911222333',
            'delivery_type'  => 'recojo',
            'items' => [
                [
                    'name'     => 'Cheesecake',
                    'quantity' => 1,
                    'price'    => 1.00, // manipulado a 1 sol
                ]
            ],
        ];

        $response = $this->postJson(route('pedido.store'), $payload);
        $response->assertOk();

        // El servidor debió cobrar el precio real (14.00), no 1.00
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Carlos',
            'total'         => 14.00,
        ]);
    }

    public function test_order_creation_requires_valid_items_and_contact(): void
    {
        $response = $this->postJson(route('pedido.store'), [
            'customer_name' => '',
            'delivery_type' => 'delivery',
            'items' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['customer_name', 'customer_phone', 'address', 'items']);
    }

    public function test_table_and_takeaway_orders_do_not_require_phone(): void
    {
        $category = Category::create([
            'name' => 'Café',
            'slug' => 'cafe',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Cappuccino',
            'slug' => 'cappuccino',
            'price' => 10.00,
            'cost_price' => 4.00,
            'stock' => 30,
            'is_active' => true,
            'available_in_store' => true,
        ]);

        // Pedido en mesa sin teléfono
        $responseMesa = $this->postJson(route('pedido.store'), [
            'customer_name' => 'Camila',
            'delivery_type' => 'mesa',
            'table_number'  => 'S2',
            'items'         => [['name' => 'Cappuccino', 'quantity' => 1]],
        ]);

        $responseMesa->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('orders', ['customer_name' => 'Camila', 'customer_phone' => null]);

        // Pedido para llevar (recojo) sin teléfono
        $responseRecojo = $this->postJson(route('pedido.store'), [
            'customer_name' => 'Mateo',
            'delivery_type' => 'recojo',
            'items'         => [['name' => 'Cappuccino', 'quantity' => 1]],
        ]);

        $responseRecojo->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('orders', ['customer_name' => 'Mateo', 'customer_phone' => null]);
    }
}
