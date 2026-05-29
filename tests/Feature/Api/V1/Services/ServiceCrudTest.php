<?php

namespace Tests\Feature\Api\V1\Services;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class ServiceCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/services')->assertStatus(401);
    }

    public function test_company_role_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/services')->assertStatus(403);
    }

    public function test_admin_lists_services(): void
    {
        Service::create(['name' => 'Reg', 'slug' => 'reg']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/admin/services')
            ->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_admin_shows_single_service(): void
    {
        $svc = Service::create(['name' => 'Reg', 'slug' => 'reg', 'description' => 'Renewal']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/admin/services/'.$svc->id)
            ->assertOk()
            ->assertJsonPath('data.id', $svc->id)
            ->assertJsonPath('data.name', 'Reg')
            ->assertJsonPath('data.slug', 'reg');
    }

    public function test_show_404_for_missing_service(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/services/999999')->assertStatus(404);
    }

    public function test_admin_can_create_and_uniqueness_enforced(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/services', ['name' => 'Renewal', 'slug' => 'renewal'])
            ->assertStatus(201)->assertJsonPath('data.slug', 'renewal');
        $this->postJson('/api/v1/admin/services', ['name' => 'Renewal2', 'slug' => 'renewal'])
            ->assertStatus(422)->assertJsonValidationErrors('slug');
    }

    public function test_update_persists(): void
    {
        $svc = Service::create(['name' => 'Old', 'slug' => 'old']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->putJson('/api/v1/admin/services/'.$svc->id, ['name' => 'New'])
            ->assertOk()->assertJsonPath('data.name', 'New');
        $this->assertDatabaseHas('services', ['id' => $svc->id, 'name' => 'New']);
    }

    public function test_update_status_endpoint(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/services/'.$svc->id.'/status', ['status_id' => 2])
            ->assertOk()->assertJsonPath('data.status_id', 2);
    }

    public function test_destroy(): void
    {
        $svc = Service::create(['name' => 'X', 'slug' => 'x']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->deleteJson('/api/v1/admin/services/'.$svc->id)->assertNoContent();
        $this->assertDatabaseMissing('services', ['id' => $svc->id]);
    }
}
