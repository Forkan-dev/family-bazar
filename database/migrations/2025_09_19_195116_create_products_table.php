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
            $table->decimal('sell_price', 8, 2)->nullable();
            $table->decimal('quantity', 8, 2)->comment('if the product is is 500g the unit will be g and the quantity will be 500'); // Numeric quantity (e.g., 3)
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->decimal('stock_quantity', 8, 2)->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_taxable')->default(false);
            $table->boolean('is_cod_available')->default(true);
            $table->boolean('is_refundable')->default(true);
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
