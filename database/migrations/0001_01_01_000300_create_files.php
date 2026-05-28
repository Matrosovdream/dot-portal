<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->foreignId('user_id')->nullable();
            $table->text('search_index')->nullable();
            $table->timestamps();
        });

        Schema::create('file_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id');
            $table->string('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_tags');
        Schema::dropIfExists('files');
    }
};
