<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('company_user_id')->nullable();
            $table->integer('profile_photo_id')->nullable();
            $table->date('dob')->nullable();
            $table->string('ssn')->nullable();
            $table->date('hire_date')->nullable();
            $table->foreignId('driver_type_id')->nullable();
            $table->integer('status_id')->default(1)->nullable(); // 1 active, 2 inactive, 3 terminated
            $table->text('search_index')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->timestamps();
        });

        Schema::create('driver_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->foreignId('file_id');
            $table->string('extension')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_license', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->foreignId('type_id')->nullable();
            $table->foreignId('endorsement_id')->nullable();
            $table->string('license_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->foreignId('state_id')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_cdl_license', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->string('license_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->foreignId('file_id')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('state_id')->nullable();
            $table->string('zip')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_medical_card', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->string('examiner_name')->nullable();
            $table->string('national_registry')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_drug_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->string('test_type')->nullable();
            $table->date('test_date')->nullable();
            $table->string('result')->nullable();
            $table->integer('file_id')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_mvr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->string('mvr_number')->nullable();
            $table->date('mvr_date')->nullable();
            $table->foreignId('file_id')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('driver_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->text('comment')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_history');
        Schema::dropIfExists('driver_meta');
        Schema::dropIfExists('driver_mvr');
        Schema::dropIfExists('driver_drug_test');
        Schema::dropIfExists('driver_medical_card');
        Schema::dropIfExists('driver_address');
        Schema::dropIfExists('driver_cdl_license');
        Schema::dropIfExists('driver_license');
        Schema::dropIfExists('driver_documents');
        Schema::dropIfExists('drivers');
    }
};
