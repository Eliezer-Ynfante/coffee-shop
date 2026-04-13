<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Un customer es la capa de perfil sobre el User de Laravel Auth.
            // Un User puede no ser customer (ej: barista, admin).
            $table->foreignId('user_id')
                  ->nullable()          // Clientes creados manualmente desde POS no tienen User
                  ->unique()            // Un User → un Customer máximo
                  ->constrained()
                  ->nullOnDelete();

            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('phone', 20)->nullable()->unique();
            $table->date('birth_date')->nullable();

            // Dirección de envío (para e-commerce)
            $table->string('address_line1', 200)->nullable();
            $table->string('address_line2', 200)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('district', 80)->nullable();  // Distrito (Perú)

            $table->text('notes')->nullable();           // Notas internas del staff
            $table->unsignedInteger('loyalty_points')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index(['first_name', 'last_name']);
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};