<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type')->nullable();
            $table->string('status')->default('unread');
            $table->foreignId('user_id');
            $table->foreignId('user_id_to')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('user_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->unique();
            $table->foreignId('user_id');
            $table->foreignId('assigned_to')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('priority')->default('normal')->nullable();
            $table->string('link')->nullable();
            $table->string('entity')->nullable();
            $table->string('tab')->nullable();
            $table->string('entity_id')->nullable();
            $table->timestamps();
        });

        Schema::create('user_task_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id');
            $table->string('key');
            $table->text('value')->nullable();
        });

        Schema::create('user_query_balance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('user_company_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('amount')->default(0);
            $table->timestamps();
        });

        Schema::create('user_query_balance_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('query_balance_id');
            $table->foreignId('user_id');
            $table->foreignId('user_company_id');
            $table->integer('amount')->default(0);
            $table->string('type')->nullable();      // add | deduct
            $table->string('initiator')->nullable();
            $table->integer('initiator_id')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_query_balance_history');
        Schema::dropIfExists('user_query_balance');
        Schema::dropIfExists('user_task_meta');
        Schema::dropIfExists('user_tasks');
        Schema::dropIfExists('notification_meta');
        Schema::dropIfExists('notifications');
    }
};
