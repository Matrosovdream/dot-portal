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
            $table->string('gateway_id')->on('payment_gateways');
            $table->string('key');
            $table->text('value');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('payment_gateway_settings');
    }
};
