<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── carts ────────────────────────────────────────────────
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Nullable: clientes guest usan session_id en lugar de customer_id
            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            // Identificador de sesión para carritos de invitados
            $table->string('session_id', 100)->nullable()->index();

            $table->enum('status', ['active', 'abandoned', 'converted'])
                  ->default('active');

            // Expiración del carrito (útil para limpiar carritos abandonados)
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // Un customer solo puede tener un carrito activo
            $table->unique(['customer_id', 'status']);
            $table->index('status');
            $table->index('expires_at');
        });

        // ── cart_items ───────────────────────────────────────────
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')
                  ->constrained()
                  ->cascadeOnDelete();   // Al borrar el carrito, se borran los items

            $table->foreignId('product_id')
                  ->constrained()
                  ->restrictOnDelete();  // No borrar producto si está en carrito activo

            $table->unsignedInteger('quantity');

            // Precio capturado al momento de agregar — protege contra cambios de precio
            $table->decimal('price', 8, 2);

            $table->timestamps();

            // Un producto no puede repetirse en el mismo carrito
            $table->unique(['cart_id', 'product_id']);
            $table->index('cart_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};