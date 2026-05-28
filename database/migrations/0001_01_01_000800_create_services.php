<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_title')->nullable();
            $table->foreignId('status_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->string('form_type')->nullable();
            $table->integer('form_id')->nullable();
            $table->timestamps();
        });

        Schema::create('service_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('service_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id');
            $table->foreignId('service_id');
            $table->string('entity')->nullable();
            $table->string('section')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('required')->default(false);
            $table->string('default_value')->nullable();
            $table->string('classes')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id');
            $table->foreignId('group_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_groups');
        Schema::dropIfExists('service_fields');
        Schema::dropIfExists('service_meta');
        Schema::dropIfExists('services');
    }
};
