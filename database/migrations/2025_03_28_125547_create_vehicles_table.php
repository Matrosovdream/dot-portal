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
            $table->date('reg_expire_date')->nullable();
            $table->date('inspection_expire_date')->nullable();
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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('ref_vehicle_unit_type');
        Schema::dropIfExists('ref_vehicle_ownership_type');
    }
};
