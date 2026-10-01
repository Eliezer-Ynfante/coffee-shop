<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Settings (Configuraciones generales de la cafetería) ────────
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->longText('value')->nullable();
                $table->string('group', 50)->default('general');
                $table->string('type', 30)->default('text'); // text, textarea, json, boolean, image
                $table->timestamps();

                $table->index('key');
                $table->index('group');
            });
        }

        // ── 2. Gallery Items (Galería fotográfica) ────────────────────────
        if (!Schema::hasTable('gallery_items')) {
            Schema::create('gallery_items', function (Blueprint $table) {
                $table->id();
                $table->string('title', 150);
                $table->string('category', 50); // cafe, ambiente, postres, procesos
                $table->string('category_name', 100)->nullable();
                $table->text('description')->nullable();
                $table->string('image_url', 500);
                $table->string('badge', 50)->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('category');
                $table->index('is_active');
                $table->index('sort_order');
            });
        }

        // ── 3. Cafe Tables (Mesas de la maqueta 3D) ───────────────────────
        if (!Schema::hasTable('cafe_tables')) {
            Schema::create('cafe_tables', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->unique(); // B1, S1, T1, CW1, etc.
                $table->string('zone', 50); // barra, salon, terraza, coworking
                $table->string('zone_name', 100)->nullable();
                $table->string('name', 100);
                $table->unsignedSmallInteger('capacity')->default(2);
                $table->string('status', 30)->default('disponible'); // disponible, ocupada, reservada, mantenimiento
                $table->unsignedSmallInteger('coord_x')->default(0);
                $table->unsignedSmallInteger('coord_y')->default(0);
                $table->string('icon', 50)->default('fa-solid fa-chair');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('zone');
                $table->index('status');
                $table->index('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_tables');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('settings');
    }
};
