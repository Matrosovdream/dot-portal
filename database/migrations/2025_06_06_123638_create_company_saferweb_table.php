<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('company_saferweb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users')->nullable();
            $table->foreignId('company_id')->on('user_company')->nullable();
            $table->string('dot_number')->nullable();
            $table->string('mc_number')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('dba_name')->nullable();
            $table->string('entity_type')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('mailing_address')->nullable();
            $table->date('latest_update')->nullable();
            $table->json('api_data')->nullable(); // Store API response data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_saferweb');
    }
};
