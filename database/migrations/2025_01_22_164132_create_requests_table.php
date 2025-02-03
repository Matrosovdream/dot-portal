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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->on('users');
            $table->foreignId('status_id')->on('request_statuses');
            $table->timestamps();
        });

        Schema::create('request_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('requests');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->on('requests');
            $table->foreignId('status_id')->on('request_statuses');
            $table->text('comment')->nullable();
            $table->foreignId('user_id')->on('users');
            $table->timestamps();
        });

        Schema::create('ref_request_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('color');
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
        Schema::dropIfExists('request_meta');
        Schema::dropIfExists('request_history');
        Schema::dropIfExists('ref_request_statuses');
    }
};
