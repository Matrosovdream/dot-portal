<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Traits\RoleFixtures;
use Tests\Feature\Api\V1\ApiTestCase;

class MeTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_gets_401(): void
    {
        $this->getJson('/api/v1/auth/me')->assertStatus(401);
    }

    public function test_authenticated_returns_user_with_roles_and_flags(): void
    {
        $user = $this->makeUserWithRole('admin');
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.flags.is_admin', true)
            ->assertJsonPath('data.flags.is_driver', false)
            ->assertJsonStructure([
                'data' => ['id', 'email', 'roles', 'flags' => ['is_admin', 'is_manager', 'is_company', 'is_driver']],
            ]);
    }
}
