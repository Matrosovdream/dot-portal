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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->float('price')->default(0);
            $table->float('price_per_driver')->default(0);
            $table->boolean('is_custom_price')->default(false);
            $table->integer('drivers_amount_from')->nullable();
            $table->integer('drivers_amount_to')->nullable();
            $table->float('discount')->default(0);
            $table->string('duration')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        }); 

        // Subscription points
        Schema::create('subscription_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->on('subscriptions');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('included')->default(1);
            $table->timestamps();
        });

        // Plan fees
        Schema::create('plan_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_role_id')->on('roles')->nullable();
            $table->timestamps();
        });

        // Subscription custom requests
        Schema::create('subscription_custom_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->foreignId('subscription_id')->on('subscriptions')->nullable();
            $table->foreignId('user_subscription_id')->on('user_subscription')->nullable();
            $table->text('request_details')->nullable();
            $table->integer('status_id')->default(1);
            $table->timestamps();
        });

    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_points');
        Schema::dropIfExists('plan_fees');
        Schema::dropIfExists('subscription_custom_requests');
    }
};
