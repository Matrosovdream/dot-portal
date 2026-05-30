<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class SettingsTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/settings')
            ->assertStatus(401);
    }

    public function test_non_admin_forbidden(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/admin/settings')
            ->assertStatus(403);
    }

    public function test_admin_blocked_when_inactive(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin', ['is_active' => false]));

        $this->getJson('/api/v1/admin/settings')
            ->assertStatus(409);
    }

    public function test_admin_can_get_settings(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        SiteSettings::create(['key' => 'sitename', 'value' => 'Acme']);

        $this->getJson('/api/v1/admin/settings')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'sitename',
                    'email',
                    'phone',
                    'address',
                    'work_time',
                    'copyright_text',
                ],
            ])
            ->assertJsonPath('data.sitename', 'Acme')
            ->assertJsonPath('data.email', '');
    }

    public function test_admin_put_persists(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->putJson('/api/v1/admin/settings', [
            'sitename' => 'New Name',
            'email' => 'a@b.com',
        ])
            ->assertOk()
            ->assertJsonPath('data.sitename', 'New Name')
            ->assertJsonPath('data.email', 'a@b.com');

        $this->assertDatabaseHas('site_settings', [
            'key' => 'sitename',
            'value' => 'New Name',
        ]);

        $this->assertDatabaseHas('site_settings', [
            'key' => 'email',
            'value' => 'a@b.com',
        ]);
    }

    public function test_admin_put_ignores_unknown_keys(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->putJson('/api/v1/admin/settings', [
            'sitename' => 'Kept',
            'malicious_key' => 'should-not-persist',
        ])
            ->assertOk()
            ->assertJsonPath('data.sitename', 'Kept');

        $this->assertDatabaseMissing('site_settings', [
            'key' => 'malicious_key',
        ]);
    }
}
