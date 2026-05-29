<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class SubManagerTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/user-subscriptions')->assertStatus(401);
    }

    public function test_company_blocked(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/user-subscriptions')->assertStatus(403);
    }

    public function test_full_crud_and_actions(): void
    {
        $user = $this->makeUserWithRole('company');
        $plan = Subscription::create(['name' => 'P', 'slug' => 'p', 'price_per_driver' => 5]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $created = $this->postJson('/api/v1/admin/user-subscriptions', [
            'user_id' => $user->id,
            'subscription_id' => $plan->id,
            'drivers_number' => 4,
            'price_per_driver' => 5,
        ])->assertStatus(201)->json('data');

        $this->putJson('/api/v1/admin/user-subscriptions/'.$created['id'], ['drivers_number' => 6])
            ->assertOk()->assertJsonPath('data.drivers_number', 6);

        $this->postJson('/api/v1/admin/user-subscriptions/'.$created['id'].'/send-onetime')
            ->assertOk()->assertJsonStructure(['message']);
        $this->postJson('/api/v1/admin/user-subscriptions/'.$created['id'].'/send-payment-link')
            ->assertOk()->assertJsonStructure(['message']);

        $this->deleteJson('/api/v1/admin/user-subscriptions/'.$created['id'])->assertNoContent();
        $this->assertDatabaseMissing('user_subscription', ['id' => $created['id']]);
    }

    public function test_admin_lists_user_subscriptions(): void
    {
        $user = $this->makeUserWithRole('company');
        $plan = Subscription::create(['name' => 'P', 'slug' => 'p', 'price_per_driver' => 5]);
        $sub = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $plan->id,
            'price_per_driver' => 5,
            'drivers_number' => 2,
            'status' => 'active',
        ]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        // UserObserver auto-creates a placeholder per user, so assert by fragment, not count.
        $this->getJson('/api/v1/admin/user-subscriptions')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id', 'user_id', 'subscription_id', 'subscription']]])
            ->assertJsonFragment(['id' => $sub->id]);
    }

    public function test_admin_shows_single_user_subscription(): void
    {
        $user = $this->makeUserWithRole('company');
        $plan = Subscription::create(['name' => 'P', 'slug' => 'p', 'price_per_driver' => 5]);
        $sub = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $plan->id,
            'price_per_driver' => 5,
            'drivers_number' => 4,
            'status' => 'active',
        ]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/user-subscriptions/'.$sub->id)
            ->assertOk()
            ->assertJsonPath('data.id', $sub->id)
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.subscription_id', $plan->id)
            ->assertJsonPath('data.subscription.id', $plan->id);
    }
}
