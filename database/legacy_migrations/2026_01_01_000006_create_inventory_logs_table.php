<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Nullable: movimientos automáticos (ventas) no tienen user directo
            $table->foreignId('order_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->enum('type', [
                'sale',         // Descuento por venta (POS o e-commerce)
                'restock',      // Reposición de inventario
                'adjustment',   // Ajuste manual por admin
                'waste',        // Merma o producto vencido
                'return',       // Devolución de cliente
            ]);

            // Positivo = entrada, Negativo = salida
            $table->integer('quantity');
            $table->unsignedInteger('stock_before');
            $table->unsignedInteger('stock_after');

            $table->enum('channel', ['pos', 'ecommerce', 'manual', 'system'])
                  ->default('system');

            $table->text('notes')->nullable();

            // Sin updated_at — los logs son inmutables
            $table->timestamp('created_at')->useCurrent();

            // Índices para auditoría y reportes de inventario
            $table->index('product_id');
            $table->index('type');
            $table->index('channel');
            $table->index('created_at');
            $table->index(['product_id', 'created_at']);  // Timeline por producto
            $table->index(['product_id', 'type']);        // Filtrar por tipo
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};