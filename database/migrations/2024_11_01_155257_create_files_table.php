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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->string('type')->default('file');
            $table->string('size');
            $table->string('extension');
            $table->text('description')->nullable();
            $table->string('disk')->nullable();
            $table->string('visibility')->default('public');
            $table->foreignId('user_id')->on('users')->nullable();
            $table->text('search_index')->nullable();
            $table->timestamps();
        });

        // Add the tags table
        Schema::create('file_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->on('files');
            $table->string('name');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_tags');
    }
};
