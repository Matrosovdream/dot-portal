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
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->foreignId('user_id')->on('users');
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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('driver_meta');
        Schema::dropIfExists('driver_history');
    }
};
