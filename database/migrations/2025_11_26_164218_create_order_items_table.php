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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();

            // Multi-shop support & assignment
            $table->foreignId('assigned_shop_id')->nullable()->constrained('shops');
            $table->decimal('shop_commission_amount', 10, 2)->nullable();
            $table->enum('assignment_status', ['unassigned', 'assigned', 'fulfilled'])->default('unassigned');
            $table->timestamp('assigned_at')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();

            // Order item basics
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2); // final item price after discount

            // Commission
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->boolean('is_commission_based')->default(true);

            // Discount system
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('discount_type')->nullable(); // percentage|flat|bogo|none
            $table->integer('discount_value')->nullable(); // original % or flat amount (e.g., 10%, 50 tk)

            // Offer integration
            // $table->foreignId('offer_id')->nullable()->constrained('offers');
            $table->integer('offer_id')->nullable();
            $table->json('applied_offer')->nullable(); // snapshot of offer at purchase time

           // Refund fields (only)
            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->string('refund_status')->default('none'); // 'none', 'requested', 'processed', 'completed'
            $table->text('refund_notes')->nullable();
            $table->timestamp('refunded_at')->nullable();

            // BOGO or free items
            $table->integer('free_quantity')->nullable();
            $table->json('promotion_details')->nullable(); // other promo flags for future
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
