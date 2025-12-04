<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('guest_id')->nullable();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->json('options')->nullable();
            $table->enum('action', ['added', 'updated', 'removed', 'ordered'])->default('added');
            $table->foreignId('order_id')->nullable();
            $table->timestamp('action_date')->useCurrent();
            $table->timestamp('expires_at')->nullable(); // Auto-clear after 1 month
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'action_date']);
            $table->index('expires_at');
            $table->index(['user_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_histories');
    }
};
