<?php

namespace Tests\Feature\Api\V1\References;

use App\Models\RefCountryStates;
use App\Models\RefDriverType;
use App\Models\RefVehicleUnitType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class ReferencesTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/references/states')->assertStatus(401);
    }

    public function test_states_returns_list(): void
    {
        RefCountryStates::create(['name' => 'California', 'code' => 'CA', 'country_id' => 1]);
        RefCountryStates::create(['name' => 'Alabama', 'code' => 'AL', 'country_id' => 1]);

        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/states')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'meta']]]);
    }

    public function test_driver_types_returns_list(): void
    {
        RefDriverType::create(['title' => 'OTR', 'slug' => 'otr', 'is_published' => 1]);

        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/driver-types')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'OTR')
            ->assertJsonPath('data.0.slug', 'otr');
    }

    public function test_bundle_returns_all_keys(): void
    {
        RefCountryStates::create(['name' => 'CA', 'code' => 'CA', 'country_id' => 1]);
        RefVehicleUnitType::create(['name' => 'Tractor', 'code' => 'TR']);

        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/bundle')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'states',
                    'driver_types',
                    'license_types',
                    'license_endorsements',
                    'vehicle_ownership_types',
                    'vehicle_unit_types',
                    'query_prices',
                    'payment_methods',
                    'request_statuses',
                    'order_statuses',
                    'service_groups',
                ],
            ])
            ->assertJsonCount(1, 'data.states')
            ->assertJsonCount(1, 'data.vehicle_unit_types');
    }

    public function test_caching_avoids_repeated_db_hits(): void
    {
        RefCountryStates::create(['name' => 'CA', 'code' => 'CA', 'country_id' => 1]);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/states')->assertOk();

        \DB::enableQueryLog();
        $this->getJson('/api/v1/references/states')->assertOk();
        $queries = \DB::getQueryLog();
        \DB::disableQueryLog();

        // No queries against ref_country_states on the cached second call.
        $hitRef = collect($queries)->contains(fn ($q) => str_contains($q['query'], 'ref_country_states'));
        $this->assertFalse($hitRef, 'Reference state list was not served from cache');
    }
}
