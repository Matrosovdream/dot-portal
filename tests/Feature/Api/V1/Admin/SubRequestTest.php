<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\Subscription;
use App\Models\SubscriptionCustomRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class SubRequestTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/sub-requests')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/sub-requests')->assertStatus(403);
    }

    public function test_admin_full_crud_and_send_email(): void
    {
        $user = $this->makeUserWithRole('company');
        $plan = Subscription::create(['name' => 'P', 'slug' => 'p']);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $created = $this->postJson('/api/v1/admin/sub-requests', [
            'user_id' => $user->id,
            'subscription_id' => $plan->id,
            'request_details' => 'Need 50 drivers',
        ])->assertStatus(201)->json('data');

        $this->putJson('/api/v1/admin/sub-requests/'.$created['id'], ['status_id' => 2])
            ->assertOk()->assertJsonPath('data.status_id', 2);

        $this->postJson('/api/v1/admin/sub-requests/'.$created['id'].'/send-email')
            ->assertOk()->assertJsonStructure(['message']);

        $this->deleteJson('/api/v1/admin/sub-requests/'.$created['id'])->assertNoContent();
        $this->assertDatabaseMissing('subscription_custom_requests', ['id' => $created['id']]);
    }

    public function test_validation_user_id_required(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/sub-requests', [])
            ->assertStatus(422)->assertJsonValidationErrors('user_id');
    }
}
