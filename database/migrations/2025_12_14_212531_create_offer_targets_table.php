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
        Schema::create('offer_targets', function (Blueprint $table) {
            // id: BIGINT PK auto-increment
            $table->id();

            // Reference to offers table
            $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');

            // Target entity (product or category) and id
            $table->string('target_type');

            // Assuming target_id refers to the id in the target entity's table
            $table->unsignedBigInteger('target_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_targets');
    }
};
