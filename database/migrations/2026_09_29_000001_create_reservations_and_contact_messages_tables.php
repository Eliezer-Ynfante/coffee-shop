<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->string('email', 150)->nullable();
                $table->string('telefono', 30);
                $table->date('fecha');
                $table->string('hora', 20)->nullable();
                $table->unsignedSmallInteger('personas')->default(2);
                $table->string('mesa_id', 20)->nullable();
                $table->string('zona', 50)->nullable();
                $table->string('ocasion', 50)->nullable();
                $table->text('comentarios')->nullable();
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('confirmed');
                $table->timestamps();

                $table->index('fecha');
                $table->index('status');
                $table->index('mesa_id');
            });
        }

        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->string('email', 150);
                $table->string('telefono', 30)->nullable();
                $table->string('motivo', 80);
                $table->text('mensaje');
                $table->enum('status', ['unread', 'read', 'attended'])->default('unread');
                $table->timestamps();

                $table->index('status');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('reservations');
    }
};
