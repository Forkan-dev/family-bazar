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
        Schema::create('offers', function (Blueprint $table) {
            // id: BIGINT PK auto-increment
            $table->id();

            // Offer basic fields
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('discount_type');
            $table->decimal('value', 10, 2);
 
            // Validity
            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->string('image')->nullable();

            // Optional cap for percentage discounts
            $table->decimal('max_discount_amount', 10, 2)->nullable();

            // Priority and status
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
