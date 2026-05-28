<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * All reference dictionaries the rest of the schema depends on.
 * Loaded early so subsequent migrations can use foreignId(state_id) etc. safely.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_country_states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->foreignId('country_id')->nullable();
        });

        Schema::create('ref_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('description')->nullable();
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

        Schema::create('ref_query_prices', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
            $table->float('price_per_query')->default(0);
            $table->text('rules')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('entity');
            $table->string('type')->nullable();
            $table->string('section')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('tooltip')->nullable();
            $table->text('description')->nullable();
            $table->text('default_value')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('default')->nullable();
            $table->string('classes')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_service_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('ref_driver_type', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug');
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('ref_driver_license_type', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug');
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('ref_driver_license_endrs', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug');
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('ref_vehicle_unit_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
        });

        Schema::create('ref_vehicle_ownership_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_vehicle_ownership_type');
        Schema::dropIfExists('ref_vehicle_unit_type');
        Schema::dropIfExists('ref_driver_license_endrs');
        Schema::dropIfExists('ref_driver_license_type');
        Schema::dropIfExists('ref_driver_type');
        Schema::dropIfExists('ref_service_groups');
        Schema::dropIfExists('ref_form_fields');
        Schema::dropIfExists('ref_query_prices');
        Schema::dropIfExists('ref_request_statuses');
        Schema::dropIfExists('ref_order_statuses');
        Schema::dropIfExists('ref_payment_methods');
        Schema::dropIfExists('ref_country_states');
    }
};
