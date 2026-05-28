<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\LoginToken;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class OneTimeLoginTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_invalid_token_returns_404(): void
    {
        $this->getJson('/api/v1/auth/login-onetime/not-a-real-token')
            ->assertStatus(404)
            ->assertJson(['message' => 'Invalid or expired token.']);
    }

    public function test_valid_token_logs_user_in_and_returns_user(): void
    {
        $user = $this->makeUserWithRole('company', ['email' => 'onetime@example.com']);
        $token = app(UserService::class)->generateLoginToken($user->id);

        $this->getJson('/api/v1/auth/login-onetime/'.$token)
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.email', 'onetime@example.com');

        // Token is single-use — must be consumed.
        $this->assertEquals(0, LoginToken::where('token', $token)->value('max_uses'));
    }

    public function test_expired_token_returns_404(): void
    {
        $user = $this->makeUserWithRole('company');
        $token = app(UserService::class)->generateLoginToken($user->id);

        // Force-expire the token.
        LoginToken::where('token', $token)->update(['expires_at' => now()->subDay()]);

        $this->getJson('/api/v1/auth/login-onetime/'.$token)
            ->assertStatus(404);
    }
}
