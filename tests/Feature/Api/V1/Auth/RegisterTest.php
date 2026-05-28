<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\RoleFixtures;
use Tests\Feature\Api\V1\ApiTestCase;

class RegisterTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_register_creates_user_and_logs_in(): void
    {
        $this->seedRoles();

        $payload = [
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'email'     => 'john@example.com',
            'phone'     => '5551234567',
            'password'  => 'password',
            'password_confirmation' => 'password',
            'role'      => 'company',
        ];

        $this->postJson('/api/v1/auth/register', $payload)
            ->assertStatus(201)
            ->assertJsonPath('data.email', 'john@example.com')
            ->assertJsonPath('data.flags.is_company', true);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        $this->makeUserWithRole('company', ['email' => 'taken@example.com']);

        $this->postJson('/api/v1/auth/register', [
            'firstname' => 'X',
            'lastname'  => 'Y',
            'email'     => 'taken@example.com',
            'password'  => 'password',
            'password_confirmation' => 'password',
            'role'      => 'company',
        ])->assertStatus(422)
          ->assertJsonValidationErrors('email');
    }

    public function test_register_validates_password_confirmation(): void
    {
        $this->seedRoles();

        $this->postJson('/api/v1/auth/register', [
            'firstname' => 'X',
            'lastname'  => 'Y',
            'email'     => 'new@example.com',
            'password'  => 'password',
            'password_confirmation' => 'different',
            'role'      => 'company',
        ])->assertStatus(422)
          ->assertJsonValidationErrors('password');
    }
}
