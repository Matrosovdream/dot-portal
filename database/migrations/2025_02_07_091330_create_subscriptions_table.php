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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            $table->string('duration')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer('drivers_amount')->nullable();
            $table->timestamps();
        }); 

        // Subscription points
        Schema::create('subscription_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->on('subscriptions');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('included')->default(1);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_points');
    }
};
