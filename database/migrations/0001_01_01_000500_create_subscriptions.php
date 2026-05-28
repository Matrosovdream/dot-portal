<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

        Schema::create('subscription_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('included')->default(1);
            $table->timestamps();
        });

        Schema::create('plan_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_role_id')->nullable();
            $table->timestamps();
        });

        Schema::create('user_subscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('subscription_id')->nullable();
            $table->float('price')->default(0);
            $table->float('price_per_driver')->default(0);
            $table->integer('drivers_number')->default(0);
            $table->float('discount')->default(0);
            $table->foreignId('payment_card_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('next_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('user_subscription_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('user_subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('subscription_id')->nullable();
            $table->foreignId('user_subscription_id')->nullable();
            $table->foreignId('payment_method_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamp('payment_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('subscription_custom_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('subscription_id')->nullable();
            $table->foreignId('user_subscription_id')->nullable();
            $table->text('request_details')->nullable();
            $table->integer('status_id')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_custom_requests');
        Schema::dropIfExists('user_subscription_payments');
        Schema::dropIfExists('user_subscription_meta');
        Schema::dropIfExists('user_subscription');
        Schema::dropIfExists('plan_fees');
        Schema::dropIfExists('subscription_points');
        Schema::dropIfExists('subscriptions');
    }
};
