<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class SubPlanTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/sub-plans')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/sub-plans')->assertStatus(403);
    }

    public function test_admin_full_crud(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $created = $this->postJson('/api/v1/admin/sub-plans', [
            'name' => 'Basic', 'slug' => 'basic', 'price_per_driver' => 9.99,
        ])->assertStatus(201)->json('data');

        $this->putJson('/api/v1/admin/sub-plans/'.$created['id'], ['name' => 'Basic+'])
            ->assertOk()->assertJsonPath('data.name', 'Basic+');

        $this->getJson('/api/v1/admin/sub-plans')->assertOk()->assertJsonCount(1, 'data');
        $this->deleteJson('/api/v1/admin/sub-plans/'.$created['id'])->assertNoContent();
    }

    public function test_uniqueness_on_slug(): void
    {
        Subscription::create(['name' => 'A', 'slug' => 'a']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/sub-plans', ['name' => 'A2', 'slug' => 'a'])
            ->assertStatus(422)->assertJsonValidationErrors('slug');
    }
}
