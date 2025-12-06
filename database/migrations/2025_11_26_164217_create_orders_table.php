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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // -----------------------------
            // Customer & Delivery Info
            // -----------------------------
            $table->foreignId('customer_id')->constrained('customers');
                // The customer who placed the order
            $table->foreignId('customer_address_id')->nullable()->constrained('customer_addresses');
                // Optional reference to saved customer address
            $table->json('delivery_address')->nullable();
                // Snapshot of the address used at order time (immutable)

            $table->foreignId('zone_id')->nullable()->constrained('zones');
                // Delivery zone / branch (optional)

            // -----------------------------
            // Order Totals & Discounts
            // -----------------------------
            $table->decimal('total_amount', 10, 2);
                // Total price of all items before discounts
            $table->decimal('discount_amount', 10, 2)->default(0);
                // Total discount applied
                // NULL/0 if no order-level discount applied (item-level discounts are in order_items)
            $table->string('discount_type')->nullable();
                // 'percentage', 'flat', 'bogo', etc.
                // NULL if no order-level discount applied
            $table->integer('discount_value')->nullable();
                // Raw value of order-level discount (e.g., 10 for 10% or 50 TK)
                // NULL if no order-level discount applied

            // -----------------------------
            // Offer References
            // -----------------------------
            // $table->foreignId('offer_id')->nullable()->constrained('offers');
            $table->integer('offer_id')->nullable();
                // NULL if no order-level offer applied
            $table->json('applied_offer')->nullable();
                // Snapshot of the offer at purchase time
                // NULL if no order-level offer applied

            // -----------------------------
            // Payment Info
            // -----------------------------
            $table->string('payment_method')->nullable();
                // e.g., cash, card, bkash
            $table->string('payment_status')->default('pending');
                // pending, paid, refunded

            // -----------------------------
            // Order Status
            // -----------------------------
            $table->string('status')->default('pending');
                // pending, processing, completed, cancelled

            // -----------------------------
            // Notes
            // -----------------------------
            $table->text('notes')->nullable();
                // Any special instructions from customer or admin

            // -----------------------------
            // Multi-Shop & Commission Info
            // -----------------------------
            $table->decimal('total_commission_amount', 10, 2)->default(0);
                // Sum of all shop commissions if items come from multiple shops

            // -----------------------------
            // Timestamps
            // -----------------------------
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
