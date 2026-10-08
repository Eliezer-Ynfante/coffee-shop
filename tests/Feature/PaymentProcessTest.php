<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentProcessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function createSampleOrder(): Order
    {
        $category = Category::create([
            'name' => 'Bebidas',
            'slug' => 'bebidas',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Latte Vainilla',
            'slug' => 'latte-vainilla',
            'price' => 12.00,
            'cost_price' => 4.00,
            'stock' => 50,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number'    => 'WEB-TEST-1001',
            'channel'         => 'ecommerce',
            'status'          => 'pending',
            'subtotal'        => 12.00,
            'discount_amount' => 0,
            'tax_amount'      => 0,
            'total'           => 12.00,
            'payment_status'  => 'pending',
            'customer_name'   => 'Camila R.',
            'notes'           => 'Modalidad: Mesa (S1)',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity'   => 1,
            'unit_price' => 12.00,
            'subtotal'   => 12.00,
        ]);

        return $order;
    }

    public function test_can_process_payment_successfully_and_reconcile_order(): void
    {
        $order = $this->createSampleOrder();

        $payload = [
            'payment_method'        => 'yape',
            'transaction_reference' => 'YAPE-889900',
            'payment_voucher'       => UploadedFile::fake()->create('voucher.jpg', 1, 'image/jpeg'),
        ];

        $response = $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), $payload);

        $response->assertOk()->assertJsonPath('success', true);

        // La orden debe haberse actualizado a pagada y confirmada
        $this->assertDatabaseHas('orders', [
            'id'             => $order->id,
            'payment_status' => 'paid',
            'status'         => 'confirmed',
            'payment_method' => 'yape',
        ]);

        // Debe registrarse el cobro inmutable en payments
        $this->assertDatabaseHas('payments', [
            'order_id'              => $order->id,
            'payment_method'        => 'yape',
            'transaction_reference' => 'YAPE-889900',
            'amount'                => 12.00,
            'status'                => 'completed',
        ]);
    }

    public function test_cannot_pay_already_paid_order_duplicate_prevention(): void
    {
        $order = $this->createSampleOrder();

        // Primer pago
        $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), [
            'payment_method'        => 'plin',
            'transaction_reference' => 'PLIN-112233',
            'payment_voucher'       => UploadedFile::fake()->create('voucher.jpg', 1, 'image/jpeg'),
        ])->assertOk();

        // Segundo intento de pago debe ser rechazado
        $response = $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), [
            'payment_method'        => 'plin',
            'transaction_reference' => 'PLIN-112233',
            'payment_voucher'       => UploadedFile::fake()->create('voucher.jpg', 1, 'image/jpeg'),
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('payment_method');
        $this->assertDatabaseCount('payments', 1);
    }

    public function test_admin_can_refund_paid_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $order = $this->createSampleOrder();

        // Pagar la orden
        $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), [
            'payment_method' => 'card',
            'card_holder'    => 'Camila R.',
            'card_number'    => '4111 1111 1111 1111',
            'card_exp'       => '12/30',
            'card_cvc'       => '123',
        ])->assertOk();

        // Reembolsar la orden
        $refundResponse = $this->postJson(route('pedido.reembolsar', ['order_number' => $order->order_number]), [
            'reason' => 'Cliente canceló el pedido por demora',
        ]);

        $refundResponse->assertOk()->assertJsonPath('success', true);

        $this->assertDatabaseHas('orders', [
            'id'             => $order->id,
            'payment_status' => 'refunded',
            'status'         => 'refunded',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status'   => 'refunded',
            'refund_reason' => 'Cliente canceló el pedido por demora',
        ]);
    }

    public function test_payment_requires_the_fields_for_the_selected_method(): void
    {
        $order = $this->createSampleOrder();

        $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), [
            'payment_method' => 'yape',
        ])->assertUnprocessable()->assertJsonValidationErrors(['transaction_reference', 'payment_voucher']);

        $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), [
            'payment_method' => 'cash',
        ])->assertUnprocessable()->assertJsonValidationErrors('cash_code');

        $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), [
            'payment_method' => 'card',
        ])->assertUnprocessable()->assertJsonValidationErrors(['card_holder', 'card_number', 'card_exp', 'card_cvc']);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_status' => 'pending']);
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_cash_payment_uses_the_entered_code_as_its_reference(): void
    {
        $order = $this->createSampleOrder();

        $this->postJson(route('pedido.pagar', ['order_number' => $order->order_number]), [
            'payment_method' => 'cash',
            'cash_code' => 'CAJA-7788',
        ])->assertOk()->assertJsonPath('data.transaction_reference', 'CAJA-7788');

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_method' => 'cash',
            'transaction_reference' => 'CAJA-7788',
            'status' => 'completed',
        ]);
    }
}
