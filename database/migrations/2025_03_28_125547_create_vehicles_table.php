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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_type_id')->on('ref_vehicle_unit_type')->nullable();
            $table->string('number')->nullable();
            $table->string('vin')->nullable();
            $table->foreignId('ownership_type_id')->on('ref_vehicle_ownership_type')->nullable();
            $table->foreignId('driver_id')->on('drivers');
            $table->foreignId('company_id')->on('users');
            $table->date('reg_expire_date')->nullable();
            $table->date('inspection_expire_date')->nullable();
            $table->integer('profile_photo_id')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_mvr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->on('vehicles');
            $table->string('mvr_number')->nullable();
            $table->date('mvr_date')->nullable();
            $table->foreignId('file_id')->on('files')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->on('vehicles');
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->foreignId('file_id')->on('files');
            $table->string('extension')->nullable();
            $table->timestamps();
        });

        // Vehicle unit type
        Schema::create('ref_vehicle_unit_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
        });

        // Vehicle ownership type
        Schema::create('ref_vehicle_ownership_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
        });

        Schema::create('vehicle_insurance_link', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->on('vehicles');
            $table->foreignId('insurance_id')->on('insurances_vehicle');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vehicle_mvr');
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('ref_vehicle_unit_type');
        Schema::dropIfExists('ref_vehicle_ownership_type');
        Schema::dropIfExists('vehicle_insurance_link');
    }
};
