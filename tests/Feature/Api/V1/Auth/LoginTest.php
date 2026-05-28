<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\RoleFixtures;
use Tests\Feature\Api\V1\ApiTestCase;

class LoginTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = $this->makeUserWithRole('company', ['email' => 'jane@example.com']);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'jane@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()->assertJsonStructure([
            'data' => ['id', 'email', 'fullname', 'flags', 'roles'],
        ]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->makeUserWithRole('company', ['email' => 'jane@example.com']);

        $this->postJson('/api/v1/auth/login', [
            'email'    => 'jane@example.com',
            'password' => 'WRONG',
        ])->assertStatus(422)
          ->assertJsonValidationErrors('email');

        $this->assertGuest();
    }

    public function test_login_validates_required_fields(): void
    {
        $this->postJson('/api/v1/auth/login', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
