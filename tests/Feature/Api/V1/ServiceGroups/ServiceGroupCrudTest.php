<?php

namespace Tests\Feature\Api\V1\ServiceGroups;

use App\Models\RefServiceGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class ServiceGroupCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/service-groups')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/service-groups')->assertStatus(403);
    }

    public function test_admin_full_crud(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $created = $this->postJson('/api/v1/admin/service-groups', [
            'name' => 'Driver', 'slug' => 'driver',
        ])->assertStatus(201)->json('data');

        $this->putJson('/api/v1/admin/service-groups/'.$created['id'], ['name' => 'Renamed'])
            ->assertOk()->assertJsonPath('data.name', 'Renamed');

        $this->deleteJson('/api/v1/admin/service-groups/'.$created['id'])->assertNoContent();
        $this->assertDatabaseMissing('ref_service_groups', ['id' => $created['id']]);
    }

    public function test_admin_shows_single_group(): void
    {
        $group = RefServiceGroup::create(['name' => 'Driver', 'slug' => 'driver']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/admin/service-groups/'.$group->id)
            ->assertOk()
            ->assertJsonPath('data.id', $group->id)
            ->assertJsonPath('data.name', 'Driver')
            ->assertJsonPath('data.slug', 'driver');
    }

    public function test_uniqueness_on_slug(): void
    {
        RefServiceGroup::create(['name' => 'X', 'slug' => 'x']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/service-groups', ['name' => 'X2', 'slug' => 'x'])
            ->assertStatus(422)->assertJsonValidationErrors('slug');
    }
}
