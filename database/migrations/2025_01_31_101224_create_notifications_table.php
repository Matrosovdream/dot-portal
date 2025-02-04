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

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type')->nullable();
            $table->string('status')->default('unread');
            $table->foreignId('user_id')->on('users');
            $table->foreignId('user_id_to')->on('users')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->on('notifications');
            $table->string('key');
            $table->text('value')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_meta');
    }
};
