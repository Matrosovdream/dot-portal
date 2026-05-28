<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\Traits\RoleFixtures;
use Tests\Feature\Api\V1\ApiTestCase;

class PasswordResetTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_request_link_sends_notification(): void
    {
        Notification::fake();
        $user = $this->makeUserWithRole('company', ['email' => 'reset@example.com']);

        $this->postJson('/api/v1/auth/password/email', ['email' => 'reset@example.com'])
            ->assertOk()
            ->assertJsonStructure(['message']);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_full_reset_flow_updates_password(): void
    {
        Notification::fake();
        $user = $this->makeUserWithRole('company', ['email' => 'reset@example.com']);

        $this->postJson('/api/v1/auth/password/email', ['email' => 'reset@example.com']);

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return true;
        });

        $this->postJson('/api/v1/auth/password/reset', [
            'token'    => $token,
            'email'    => 'reset@example.com',
            'password' => 'BrandNewPass123!',
            'password_confirmation' => 'BrandNewPass123!',
        ])->assertOk();

        $this->assertTrue(Hash::check('BrandNewPass123!', $user->fresh()->password));
    }
}
