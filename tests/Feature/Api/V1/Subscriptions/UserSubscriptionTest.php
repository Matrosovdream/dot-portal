<?php

namespace Tests\Feature\Api\V1\Subscriptions;

use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class UserSubscriptionTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/subscription')->assertStatus(401);
    }

    public function test_show_returns_placeholder_for_new_user(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        // UserObserver auto-creates a "disabled" placeholder UserSubscription.
        $this->getJson('/api/v1/subscription')
            ->assertOk()
            ->assertJsonPath('data.status', 'disabled');
    }

    public function test_update_creates_or_replaces(): void
    {
        $user = $this->makeUserWithRole('company');
        $plan = Subscription::create(['name' => 'Plan A', 'slug' => 'a', 'price_per_driver' => 10]);

        Sanctum::actingAs($user);
        $this->putJson('/api/v1/subscription', [
            'subscription_id' => $plan->id,
            'drivers_number'  => 3,
        ])->assertOk()
          ->assertJsonPath('data.subscription_id', $plan->id)
          ->assertJsonPath('data.drivers_number', 3);

        $this->assertDatabaseHas('user_subscription', [
            'user_id' => $user->id, 'subscription_id' => $plan->id, 'drivers_number' => 3,
        ]);
    }

    public function test_cancel_marks_cancelled(): void
    {
        $user = $this->makeUserWithRole('company');
        $plan = Subscription::create(['name' => 'P', 'slug' => 'p', 'price_per_driver' => 5]);
        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $plan->id,
            'price_per_driver' => 5,
            'drivers_number' => 2,
            'status' => 'active',
        ]);

        Sanctum::actingAs($user);
        $this->postJson('/api/v1/subscription/cancel')
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');
    }

    public function test_cancel_404_when_no_sub(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->postJson('/api/v1/subscription/cancel')->assertStatus(404);
    }
}
