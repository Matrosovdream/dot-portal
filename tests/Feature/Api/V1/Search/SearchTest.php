<?php

namespace Tests\Feature\Api\V1\Search;

use App\Models\Driver;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class SearchTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/search/global?q=foo')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false]));
        $this->getJson('/api/v1/search/global?q=foo')->assertStatus(409);
    }

    public function test_q_is_required(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/search/global')
            ->assertStatus(422)
            ->assertJsonValidationErrors('q');
    }

    public function test_finds_own_driver_by_name_and_scopes_out_others(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company     = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'My Co']);

        $mineUser = User::factory()->create(['firstname' => 'Alice', 'email' => 'alice@example.com']);
        Driver::create([
            'user_id'         => $mineUser->id,
            'company_id'      => $company->id,
            'company_user_id' => $companyUser->id,
            'status_id'       => 1,
        ]);

        // A driver under a different company must not surface.
        $otherCompanyUser = $this->makeUserWithRole('company');
        $otherCompany     = UserCompany::create(['user_id' => $otherCompanyUser->id, 'name' => 'Other']);
        $otherUser        = User::factory()->create(['firstname' => 'Alicia', 'email' => 'alicia@example.com']);
        Driver::create([
            'user_id'         => $otherUser->id,
            'company_id'      => $otherCompany->id,
            'company_user_id' => $otherCompanyUser->id,
            'status_id'       => 1,
        ]);

        Sanctum::actingAs($companyUser);

        $this->getJson('/api/v1/search/global?q=Alice')
            ->assertOk()
            ->assertJsonPath('data.drivers.count', 1)
            ->assertJsonCount(1, 'data.drivers.items')
            ->assertJsonPath('data.drivers.items.0.email', 'alice@example.com');
    }

    public function test_finds_vehicle_by_number(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company     = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'My Co']);

        Vehicle::create([
            'number'          => 'TRUCK-007',
            'vin'             => '1FUJA6CV',
            'company_id'      => $company->id,
            'company_user_id' => $companyUser->id,
        ]);

        Sanctum::actingAs($companyUser);

        $this->getJson('/api/v1/search/global?q=TRUCK')
            ->assertOk()
            ->assertJsonPath('data.vehicles.count', 1)
            ->assertJsonCount(1, 'data.vehicles.items')
            ->assertJsonPath('data.vehicles.items.0.number', 'TRUCK-007');
    }

    public function test_returns_empty_buckets_when_nothing_matches(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/search/global?q=zzznomatch')
            ->assertOk()
            ->assertJsonPath('data.drivers.count', 0)
            ->assertJsonPath('data.vehicles.count', 0)
            ->assertJsonPath('data.service_requests.count', 0);
    }
}
