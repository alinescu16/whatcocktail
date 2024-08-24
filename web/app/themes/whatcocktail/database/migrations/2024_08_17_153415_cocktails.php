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
        Schema::create('cocktails', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->longText('description')->nullable();
            $table->string('main_category', 255)->nullable();
            $table->string('sub_category', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('season', 255)->nullable();
            $table->string('serving_type', 255)->nullable();
            $table->json('recipe')->nullable();
            $table->json('ingredients')->nullable();
            $table->longText('preparation_instructions')->nullable();
            $table->timestamp('preparation_duration')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cocktails');
    }
};
