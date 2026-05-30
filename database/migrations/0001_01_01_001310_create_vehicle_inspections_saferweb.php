<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_inspections_saferweb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable();
            $table->string('unit_vin')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->string('dot_number')->nullable();
            $table->string('unique_id')->nullable();
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
    }
};
