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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_en'); // English name
            $table->string('name_bn')->nullable(); // Bengali name
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 8, 2);
            $table->decimal('quantity', 8, 2); // Numeric quantity (e.g., 3)
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->decimal('stock_quantity', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
