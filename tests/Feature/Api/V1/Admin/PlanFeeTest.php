<?php

namespace Tests\Feature\Api\V1\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class PlanFeeTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/plan-fees')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/plan-fees')->assertStatus(403);
    }

    public function test_admin_can_index_store_update_no_destroy(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $created = $this->postJson('/api/v1/admin/plan-fees', [
            'name' => 'Setup fee', 'price' => 49.99,
        ])->assertStatus(201)->json('data');

        $this->putJson('/api/v1/admin/plan-fees/'.$created['id'], ['price' => 59.99])
            ->assertOk()->assertJsonPath('data.price', 59.99);

        // No destroy route on this resource by design — make sure it 405s.
        $this->deleteJson('/api/v1/admin/plan-fees/'.$created['id'])->assertStatus(405);
    }
}
