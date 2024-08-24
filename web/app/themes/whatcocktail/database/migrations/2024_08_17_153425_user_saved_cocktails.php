<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Roots\WPConfig\Config;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_saved_cocktails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('cocktail_id');

            // Foreign key reference to the 'users' table
            $table->foreign('user_id')->references('id')->on('user_data')->onDelete('cascade');
            $table->foreign('cocktail_id')->references('id')->on('cocktails')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_saved_cocktails');
    }
};
