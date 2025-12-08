<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->json('title')->nullable();       // EN + BN in JSON
            $table->json('sub_title')->nullable();   // EN + BN in JSON
            $table->json('description')->nullable(); // EN + BN in JSON
            $table->integer('position')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->string('button_text_1')->nullable();
            $table->string('button_url_1')->nullable();
            $table->string('button_text_2')->nullable();
            $table->string('button_url_2')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
