<?php

namespace Tests\Feature\Api\V1\References;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class GlobalsTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/globals')->assertStatus(401);
    }

    public function test_returns_globals_payload(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/globals')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['site_name', 'currency', 'settings', 'feature_flags', 'locale'],
            ])
            ->assertJsonPath('data.feature_flags.clearing_house', true);
    }
}
