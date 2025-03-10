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
        Schema::create('user_payment_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->string('card_number')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('expiry_date')->nullable();
            $table->foreignId('payment_method_id')->on('payment_gateways')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_cards');
    }
};
