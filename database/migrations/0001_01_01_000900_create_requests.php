<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('status_id');
            $table->foreignId('service_id');
            $table->foreignId('order_id')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('request_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id');
            $table->foreignId('status_id');
            $table->text('comment')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::create('request_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id');
            $table->foreignId('field_id');
            $table->text('value')->nullable();
        });

        Schema::create('request_predefined_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id');
            $table->string('field_code')->nullable();
            $table->text('value')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_predefined_values');
        Schema::dropIfExists('request_field_values');
        Schema::dropIfExists('request_history');
        Schema::dropIfExists('request_meta');
        Schema::dropIfExists('requests');
    }
};
