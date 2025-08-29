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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users');
            $table->foreignId('status_id')->on('request_statuses');
            $table->foreignId('service_id')->on('services');
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('request_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('requests');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->on('requests');
            $table->foreignId('status_id')->on('request_statuses');
            $table->text('comment')->nullable();
            $table->foreignId('user_id')->on('users');
            $table->timestamps();
        });

        Schema::create('ref_request_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('color');
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        Schema::create('request_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->on('requests');
            $table->foreignId('field_id')->on('ref_form_fields');
            $table->text('value')->nullable();
        });

        Schema::create('request_predefined_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->on('requests');
            $table->string('field_code')->nullable();
            $table->text('value')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
        Schema::dropIfExists('request_meta');
        Schema::dropIfExists('request_history');
        Schema::dropIfExists('ref_request_statuses');
        Schema::dropIfExists('request_field_values');
        Schema::dropIfExists('request_predefined_values');
    }
    
};
