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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->foreignId('role_id')->on('users');
            $table->timestamps();
        });

        Schema::create('user_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('dot_number')->nullable();
            $table->string('mc_number')->nullable();
            $table->timestamps();
        });

        Schema::create('user_company_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('users');
            $table->string('type')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('state_id')->on('ref_country_states')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();
        });


        Schema::create('user_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('state_id')->on('ref_country_states')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();
        });

        // User subscription
        Schema::create('user_subscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->foreignId('subscription_id')->on('subscriptions');
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // User subscription meta
        Schema::create('user_subscription_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->on('user_subscription');
            $table->string('key');
            $table->text('value')->nullable();
        });

        // User Payment cards
        Schema::create('user_payment_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->string('card_number')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('expiry_date')->nullable();
            $table->foreignId('payment_method_id')->on('payment_gateways')->nullable();
            $table->boolean('primary')->default(false);
            $table->timestamps();
        });

        Schema::create('user_payment_card_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->on('user_payment_cards');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // User Payment history including single and subscription payments
        Schema::create('user_payment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->foreignId('payment_method_id')->on('payment_gateways');
            $table->foreignId('subscription_id')->on('user_subscription')->nullable();
            $table->string('type')->nullable();
            $table->float('amount')->default(0);
            $table->timestamp('payment_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('user_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('user_company');
        Schema::dropIfExists('user_company_address');
        Schema::dropIfExists('user_address');
        Schema::dropIfExists('user_subscription');
        Schema::dropIfExists('user_subscription_meta');
        Schema::dropIfExists('user_payment_cards');
        Schema::dropIfExists('user_payment_card_meta');
        Schema::dropIfExists('user_payment_history');
        Schema::dropIfExists('user_meta');
    }
};
