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

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users')->nullable();
            $table->foreignId('company_id')->on('users')->nullable();
            $table->integer('profile_photo_id')->nullable();
            $table->date('dob')->nullable();
            $table->string('ssn')->nullable();
            $table->date('hire_date')->nullable();
            $table->foreignId('driver_type_id')->on('ref_driver_type');
            $table->integer('status_id')->default(1)->nullable(); // 1 - active, 2 - inactive, 3 - terminated
            $table->timestamps();
        });

        Schema::create('driver_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->on('drivers');
            $table->string('type');
            $table->string('title')->nullable();
            $table->foreignId('file_id')->on('files');
            $table->string('extension')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_license', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->on('drivers');
            $table->foreignId('type_id')->on('ref_driver_license_type');
            $table->foreignId('endorsement_id')->on('ref_driver_license_endrs');
            $table->string('license_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->foreignId('state_id')->on('ref_country_states');
            $table->timestamps();
        });

        // CDL License
        Schema::create('driver_cdl_license', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->on('drivers');
            $table->string('license_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->foreignId('file_id')->on('files')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('drivers');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('state_id')->on('ref_country_states');
            $table->string('zip')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_medical_card', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->on('drivers');
            $table->string('examiner_name')->nullable();
            $table->string('national_registry')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });

        // Drug test
        Schema::create('driver_drug_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->on('drivers');
            $table->string('test_type')->nullable();
            $table->date('test_date')->nullable();
            $table->string('result')->nullable();
            $table->integer('file_id')->nullable();
            $table->timestamps();
        });

        // MVR
        Schema::create('driver_mvr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->on('drivers');
            $table->string('mvr_number')->nullable();
            $table->date('mvr_date')->nullable();
            $table->foreignId('file_id')->on('files')->nullable();
            $table->timestamps();
        });

        Schema::create('driver_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('services');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('driver_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('drivers');
            $table->text('comment')->nullable();
            $table->foreignId('user_id')->on('users');
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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('driver_documents');
        Schema::dropIfExists('driver_license');
        Schema::dropIfExists('driver_cdl_license');
        Schema::dropIfExists('driver_address');
        Schema::dropIfExists('driver_medical_card');
        Schema::dropIfExists('driver_drug_test');
        Schema::dropIfExists('driver_mvr');
        Schema::dropIfExists('driver_meta');
        Schema::dropIfExists('driver_history');
        Schema::dropIfExists('ref_driver_type');
        Schema::dropIfExists('ref_driver_license_type');
        Schema::dropIfExists('ref_driver_license_endrs');
    }

};
