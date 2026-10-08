<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->enum('payment_method', ['cash', 'card', 'yape', 'plin']);

            $table->string('transaction_reference', 100)->nullable();
            
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PEN');

            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])
                  ->default('pending');

            $table->string('provider', 50)->default('local_pasarela');

            $table->foreignId('confirmed_by_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('status');
            $table->index('transaction_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
