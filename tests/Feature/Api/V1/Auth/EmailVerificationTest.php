<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class EmailVerificationTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_status_requires_auth(): void
    {
        $this->getJson('/api/v1/auth/email/verify')->assertStatus(401);
    }

    public function test_status_returns_verified_true_for_verified_user(): void
    {
        $user = $this->makeUserWithRole('company', ['email_verified_at' => now()]);
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/auth/email/verify')
            ->assertOk()
            ->assertJsonPath('verified', true)
            ->assertJsonPath('email', $user->email);
    }

    public function test_status_returns_verified_false_for_unverified_user(): void
    {
        $user = $this->makeUserWithRole('company', ['email_verified_at' => null]);
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/auth/email/verify')
            ->assertOk()
            ->assertJsonPath('verified', false);
    }

    public function test_send_dispatches_notification_only_when_unverified(): void
    {
        Notification::fake();
        $user = $this->makeUserWithRole('company', ['email_verified_at' => null]);
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/auth/email/verify/resend')
            ->assertOk()
            ->assertJson(['message' => 'Verification link sent.']);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_send_is_noop_when_already_verified(): void
    {
        Notification::fake();
        $user = $this->makeUserWithRole('company', ['email_verified_at' => now()]);
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/auth/email/verify/resend')
            ->assertOk()
            ->assertJson(['message' => 'Already verified.']);

        Notification::assertNothingSent();
    }

    public function test_verify_endpoint_marks_user_verified(): void
    {
        $user = $this->makeUserWithRole('company', ['email_verified_at' => null]);
        Sanctum::actingAs($user);

        $signedUrl = URL::temporarySignedRoute(
            'api.v1.auth.verification.verify',
            now()->addHour(),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->get($signedUrl)
            ->assertOk()
            ->assertJsonPath('verified', true);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
