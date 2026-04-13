<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                  ->constrained()
                  ->restrictOnDelete(); // No borrar categoría con productos activos

            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->string('sku', 50)->unique()->nullable();  // Código interno
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();

            // Precio y costo
            $table->decimal('price', 8, 2);                  // Precio de venta
            $table->decimal('cost_price', 8, 2)->nullable();  // Costo (para margen)

            // Control de stock
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('min_stock_alert')->default(5);  // Umbral de alerta

            // Visibilidad por canal
            $table->boolean('available_in_pos')->default(true);
            $table->boolean('available_in_store')->default(true);
            $table->boolean('is_active')->default(true);

            // Metadatos opcionales
            $table->unsignedSmallInteger('preparation_time')->nullable(); // En minutos
            $table->boolean('is_featured')->default(false);               // Destacado en tienda

            $table->timestamps();
            $table->softDeletes();

            // Índices para búsquedas frecuentes del POS y e-commerce
            $table->index('is_active');
            $table->index('available_in_pos');
            $table->index('available_in_store');
            $table->index('stock');
            $table->index('is_featured');
            $table->index(['category_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};