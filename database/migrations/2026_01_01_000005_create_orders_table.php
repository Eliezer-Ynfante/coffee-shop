<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── orders ───────────────────────────────────────────────
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Código legible para el cliente y el barista
            $table->string('order_number', 20)->unique();   // CAF-2025-00001

            // Cliente que realizó el pedido (nullable para POS sin cliente registrado)
            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Carrito del que proviene (solo e-commerce; null en POS)
            $table->foreignId('cart_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Barista que atendió (solo POS; null en e-commerce)
            $table->foreignId('attendant_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->enum('channel', ['pos', 'ecommerce']);

            $table->enum('status', [
                'pending',      // Creada, esperando pago (e-commerce)
                'confirmed',    // Pago confirmado
                'preparing',    // Barista preparando
                'ready',        // Lista para entregar
                'completed',    // Entregada y finalizada
                'cancelled',    // Cancelada
                'refunded',     // Reembolsada
            ])->default('pending');

            // Desglose financiero
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->enum('payment_method', ['cash', 'card', 'yape', 'plin'])
                  ->nullable();

            $table->enum('payment_status', ['pending', 'paid', 'refunded'])
                  ->default('pending');

            // Datos del cliente para órdenes POS sin Customer registrado
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 20)->nullable();

            $table->text('notes')->nullable();

            // Timestamps de ciclo de vida de la orden
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices para reportes y dashboards
            $table->index('order_number');
            $table->index('channel');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
            $table->index(['channel', 'status']);
            $table->index(['created_at', 'channel']);   // Para reportes por día/canal
        });

        // ── order_items ──────────────────────────────────────────
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained()
                  ->restrictOnDelete();   // Preservar historial de ventas

            $table->unsignedInteger('quantity');

            // Precio histórico — no cambia aunque el producto se actualice
            $table->decimal('unit_price', 8, 2);
            $table->decimal('subtotal', 8, 2);

            // Personalizaciones del ítem (sin azúcar, extra shot, etc.)
            $table->string('notes', 200)->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};