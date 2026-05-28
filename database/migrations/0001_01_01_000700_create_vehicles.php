<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vehicles + sub-tables + insurance.
 * driver_id is NULLABLE from the start (SPA needs to support add-vehicle-without-driver).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_type_id')->nullable();
            $table->string('number')->nullable();
            $table->string('vin')->nullable();
            $table->foreignId('ownership_type_id')->nullable();
            $table->foreignId('driver_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('company_user_id')->nullable();
            $table->date('reg_expire_date')->nullable();
            $table->date('inspection_expire_date')->nullable();
            $table->integer('profile_photo_id')->nullable();
            $table->text('search_index')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->timestamps();
        });

        Schema::create('vehicle_mvr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id');
            $table->string('mvr_number')->nullable();
            $table->date('mvr_date')->nullable();
            $table->foreignId('file_id')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id');
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->foreignId('file_id');
            $table->string('extension')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id');
            $table->date('inspection_date')->nullable();
            $table->string('inspection_number')->nullable();
            $table->foreignId('file_id')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_driver_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id');
            $table->foreignId('driver_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('insurances_vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('file_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->text('search_index')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_insurance_link', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id');
            $table->foreignId('insurance_id');
        });

        Schema::create('vehicle_crashes_saferweb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable();
            $table->string('unit_vin')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->string('dot_number')->nullable();
            $table->date('report_date')->nullable();
            $table->string('report_number')->unique()->nullable();
            $table->string('report_sequence_number')->nullable();
            $table->string('report_state')->nullable();
            $table->foreignId('report_state_id')->nullable();
            $table->integer('total_injuries')->nullable();
            $table->integer('total_fatalities')->nullable();
            $table->json('api_data')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_inspections_saferweb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable();
            $table->string('unit_vin')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->string('dot_number')->nullable();
            $table->integer('unique_id')->unique()->nullable();
            $table->date('report_date')->nullable();
            $table->string('report_number')->nullable();
            $table->string('report_sequence_number')->nullable();
            $table->string('inspection_level')->nullable();
            $table->string('report_state')->nullable();
            $table->foreignId('report_state_id')->nullable();
            $table->json('api_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_inspections_saferweb');
        Schema::dropIfExists('vehicle_crashes_saferweb');
        Schema::dropIfExists('vehicle_insurance_link');
        Schema::dropIfExists('insurances_vehicle');
        Schema::dropIfExists('vehicle_driver_history');
        Schema::dropIfExists('vehicle_inspections');
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('vehicle_mvr');
        Schema::dropIfExists('vehicles');
    }
};
