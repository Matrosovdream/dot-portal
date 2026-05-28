<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Payment gateways + user payment artefacts (cards, history).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payment_gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_id');
            $table->string('key');
            $table->text('value');
            $table->timestamps();
        });

        Schema::create('user_payment_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('card_number')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('expiry_date')->nullable();
            $table->foreignId('payment_method_id')->nullable();
            $table->boolean('primary')->default(false);
            $table->timestamps();
        });

        Schema::create('user_payment_card_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('user_payment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('payment_method_id');
            $table->foreignId('subscription_id')->nullable();
            $table->string('type')->nullable();
            $table->float('amount')->default(0);
            $table->timestamp('payment_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->foreignId('request_id')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_payment_history');
        Schema::dropIfExists('user_payment_card_meta');
        Schema::dropIfExists('user_payment_cards');
        Schema::dropIfExists('payment_gateway_settings');
        Schema::dropIfExists('payment_gateways');
    }
};
