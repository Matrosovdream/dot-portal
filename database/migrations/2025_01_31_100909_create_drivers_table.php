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
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('dob')->nullable();
            $table->string('ssn')->nullable();
            $table->date('hire_date')->nullable();
            $table->foreignId('driver_type_id')->on('ref_driver_type');
            $table->foreignId('user_id')->on('users');
            $table->foreignId('company_id')->on('companies');
            $table->integer('profile_photo_id')->nullable();
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
        Schema::dropIfExists('driver_address');
        Schema::dropIfExists('driver_medical_card');
        Schema::dropIfExists('driver_meta');
        Schema::dropIfExists('driver_history');
        Schema::dropIfExists('ref_driver_type');
        Schema::dropIfExists('ref_driver_license_type');
        Schema::dropIfExists('ref_driver_license_endrs');
    }

};
