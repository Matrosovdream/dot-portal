<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Core identity: users, roles, addresses, company, one-time login tokens.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('fullname')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->string('reg_step')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('role_id');
        });

        Schema::create('user_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('state_id')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();
        });

        Schema::create('user_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('dot_number')->nullable();
            $table->string('mc_number')->nullable();
            $table->integer('trucks_number')->default(0);
            $table->integer('drivers_number')->default(0);
            $table->timestamps();
        });

        Schema::create('user_company_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');           // user_company.id
            $table->string('type')->nullable();     // business | mailing
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('state_id')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();
        });

        Schema::create('login_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->integer('max_uses')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_tokens');
        Schema::dropIfExists('user_company_address');
        Schema::dropIfExists('user_company');
        Schema::dropIfExists('user_address');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_meta');
        Schema::dropIfExists('users');
    }
};
