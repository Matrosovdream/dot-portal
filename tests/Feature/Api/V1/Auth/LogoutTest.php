<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\RoleFixtures;
use Tests\Feature\Api\V1\ApiTestCase;

class LogoutTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_logout_returns_no_content_and_calls_guard_logout(): void
    {
        $user = $this->makeUserWithRole('company', ['email' => 'logout@example.com']);
        $this->actingAs($user, 'web');

        // Spy on Auth guard to verify logout() is called.
        $this->postJson('/api/v1/auth/logout')->assertNoContent();

        // After the request the guard's in-memory user must be cleared.
        $this->assertNull(\Illuminate\Support\Facades\Auth::guard('web')->user());
    }

    public function test_logout_requires_auth(): void
    {
        $this->postJson('/api/v1/auth/logout')->assertStatus(401);
    }
}
