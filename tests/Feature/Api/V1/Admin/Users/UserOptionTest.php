<?php

namespace Tests\Feature\Api\V1\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class UserOptionTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/user-options')->assertStatus(401);
    }

    public function test_admin_can_list_minimal_shape(): void
    {
        User::factory()->create(['firstname' => 'Zoe', 'email' => 'zoe@example.com']);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/user-options')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id', 'fullname', 'email']]]);
    }

    public function test_manager_can_list(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('manager'));
        $this->getJson('/api/v1/admin/user-options')->assertOk();
    }

    public function test_company_forbidden(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/user-options')->assertStatus(403);
    }

    public function test_driver_forbidden(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('driver'));
        $this->getJson('/api/v1/admin/user-options')->assertStatus(403);
    }

    public function test_search_by_q(): void
    {
        User::factory()->create(['firstname' => 'Alice', 'email' => 'alice@example.com']);
        User::factory()->create(['firstname' => 'Bob', 'email' => 'bob@example.com']);

        Sanctum::actingAs($this->makeUserWithRole('manager'));
        $this->getJson('/api/v1/admin/user-options?q=alice')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.email', 'alice@example.com');
    }
}
