<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable()->nullable();
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->foreignId('status_id')->on('ref_request_status')->nullable();
            $table->foreignId('group_id')->on('ref_service_groups')->nullable();
            $table->string('form_type')->nullable();
            $table->timestamps();
        });

        Schema::create('service_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('services');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('service_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->on('order_fields');
            $table->foreignId('service_id')->on('services');
            $table->string('entity')->nullable();
            $table->string('section')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('required')->default(false);
            $table->string('default_value')->nullable();
            $table->string('classes')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Service groups
        Schema::create('service_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->on('services');
            $table->foreignId('group_id')->on('ref_service_groups');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_meta');
        Schema::dropIfExists('service_fields');
        Schema::dropIfExists('service_groups');
    }
};
