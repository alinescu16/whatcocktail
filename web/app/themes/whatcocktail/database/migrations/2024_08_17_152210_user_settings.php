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
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->ipAddress('ip_address');
            
            $table->boolean('subscribed_weekly')->default(false);
            $table->boolean('subscribed_monthly')->default(false);
            $table->boolean('subscribe_up_to_date')->default(false);

            $table->boolean('donated')->default(false);
            $table->decimal('donation_amount', 8, 2);
            $table->timestamp('last_entered_site')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_data');
    }
};
