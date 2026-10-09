<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['admin', 'customer'])->default('customer');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        if (! Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (! Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        if (! Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        if (! Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->integer('cancelled_at')->nullable();
                $table->integer('created_at');
                $table->integer('finished_at')->nullable();
            });
        }

        if (! Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('name', 100);
                $table->string('slug', 120)->unique();
                $table->text('description')->nullable();
                $table->string('image_path')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
                $table->index('is_active');
                $table->index('sort_order');
                $table->index('parent_id');
            });
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->restrictOnDelete();
                $table->string('name', 150);
                $table->string('slug', 170)->unique();
                $table->string('sku', 50)->unique()->nullable();
                $table->text('description')->nullable();
                $table->string('image_path')->nullable();
                $table->decimal('price', 8, 2);
                $table->decimal('cost_price', 8, 2)->nullable();
                $table->unsignedInteger('stock')->default(0);
                $table->unsignedInteger('min_stock_alert')->default(5);
                $table->boolean('available_in_pos')->default(true);
                $table->boolean('available_in_store')->default(true);
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('preparation_time')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
                $table->softDeletes();
                $table->index('is_active');
                $table->index('available_in_pos');
                $table->index('available_in_store');
                $table->index('stock');
                $table->index('is_featured');
                $table->index(['category_id', 'is_active']);
            });
        }

        if (! Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
                $table->string('first_name', 80);
                $table->string('last_name', 80);
                $table->string('phone', 20)->nullable()->unique();
                $table->date('birth_date')->nullable();
                $table->string('address_line1', 200)->nullable();
                $table->string('address_line2', 200)->nullable();
                $table->string('city', 80)->nullable();
                $table->string('district', 80)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedInteger('loyalty_points')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
                $table->index('is_active');
                $table->index(['first_name', 'last_name']);
                $table->index('phone');
            });
        }

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number', 20)->unique();
                $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('attendant_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('channel', ['pos', 'ecommerce']);
                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                    'completed',
                    'cancelled',
                    'refunded',
                ])->default('pending');
                $table->decimal('subtotal', 10, 2);
                $table->decimal('discount_amount', 10, 2)->default(0);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('total', 10, 2);
                $table->enum('payment_method', ['cash', 'card', 'yape', 'plin'])->nullable();
                $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
                $table->string('customer_name', 100)->nullable();
                $table->string('customer_phone', 20)->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('prepared_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('cancellation_reason')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index('order_number');
                $table->index('channel');
                $table->index('status');
                $table->index('payment_status');
                $table->index('created_at');
                $table->index(['channel', 'status']);
                $table->index(['created_at', 'channel']);
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->restrictOnDelete();
                $table->string('product_name', 150)->nullable();
                $table->string('product_sku', 100)->nullable();
                $table->unsignedInteger('quantity');
                $table->decimal('unit_price', 8, 2);
                $table->decimal('subtotal', 8, 2);
                $table->string('notes', 200)->nullable();
                $table->timestamps();
                $table->index('order_id');
                $table->index('product_id');
            });
        }

        if (! Schema::hasTable('inventory_logs')) {
            Schema::create('inventory_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->enum('type', ['sale', 'restock', 'adjustment', 'waste', 'return']);
                $table->integer('quantity');
                $table->unsignedInteger('stock_before');
                $table->unsignedInteger('stock_after');
                $table->enum('channel', ['pos', 'ecommerce', 'manual', 'system'])->default('system');
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->index('product_id');
                $table->index('type');
                $table->index('channel');
                $table->index('created_at');
                $table->index(['product_id', 'created_at']);
                $table->index(['product_id', 'type']);
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->enum('payment_method', ['cash', 'card', 'yape', 'plin']);
                $table->string('transaction_reference', 100)->nullable();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('PEN');
                $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
                $table->string('provider', 50)->default('local_pasarela');
                $table->foreignId('confirmed_by_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->text('refund_reason')->nullable();
                $table->timestamp('refunded_at')->nullable();
                $table->timestamps();
                $table->index('order_id');
                $table->index('status');
                $table->index('transaction_reference');
            });
        }

        if (! Schema::hasTable('cafe_tables')) {
            Schema::create('cafe_tables', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->unique();
                $table->string('zone', 50);
                $table->string('zone_name', 100)->nullable();
                $table->string('name', 100);
                $table->unsignedSmallInteger('capacity')->default(2);
                $table->string('status', 30)->default('disponible');
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

        if (! Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->string('email', 150)->nullable();
                $table->string('telefono', 30);
                $table->date('fecha');
                $table->string('hora', 20)->nullable();
                $table->unsignedSmallInteger('personas')->default(2);
                $table->string('mesa_id', 20)->nullable();
                $table->foreignId('cafe_table_id')->nullable()->constrained('cafe_tables')->nullOnDelete();
                $table->string('zona', 50)->nullable();
                $table->string('zone_code', 50)->nullable();
                $table->string('ocasion', 50)->nullable();
                $table->text('comentarios')->nullable();
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending');
                $table->timestamps();
                $table->index('fecha');
                $table->index('status');
                $table->index('mesa_id');
                $table->index(['fecha', 'hora', 'status']);
            });
        }

        if (! Schema::hasTable('contact_messages')) {
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

        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->longText('value')->nullable();
                $table->string('group', 50)->default('general');
                $table->string('type', 30)->default('text');
                $table->timestamps();
                $table->index('key');
                $table->index('group');
            });
        }

        if (! Schema::hasTable('gallery_items')) {
            Schema::create('gallery_items', function (Blueprint $table) {
                $table->id();
                $table->string('title', 150);
                $table->string('category', 50);
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

    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('cafe_tables');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('users');
    }
};
