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

        // Orders 
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('order_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->foreignId('status_id')->on('ref_order_statuses')->nullable();
            $table->foreignId('payment_method_id')->on('ref_payment_methods')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Order meta
        Schema::create('order_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('orders');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Order statuses
        Schema::create('ref_order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Order items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->on('orders');
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->string('entity')->nullable();
            $table->integer('entity_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });

        // Order payments for example Paypal, it's status etc
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->on('orders');
            $table->foreignId('payment_method_id')->on('ref_payment_methods')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status');
            $table->string('transaction_id')->nullable();
            $table->text('transaction_details')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_meta');
        Schema::dropIfExists('ref_order_statuses');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('ref_payment_methods');
    }

};
