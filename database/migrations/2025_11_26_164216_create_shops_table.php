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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_owner_id')->nullable()->constrained('shop_owners')->onDelete('set null');
            $table->string('name');
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->foreignId('zone_id')->constrained('zones');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->string('type')->comment('retail, wholesale, distributor');
            $table->boolean('is_commission_based')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
