<?php

namespace Tests\Feature\Api\V1\References;

use App\Models\RefDriverLicenseEndrs;
use App\Models\RefDriverLicenseType;
use App\Models\RefOrderStatus;
use App\Models\RefPaymentMethod;
use App\Models\RefQueryPrice;
use App\Models\RefRequestStatus;
use App\Models\RefServiceGroup;
use App\Models\RefVehicleOwnershipType;
use App\Models\RefVehicleUnitType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

/**
 * Smoke coverage for the 9 reference endpoints not exercised in ReferencesTest:
 * license types/endorsements, vehicle ownership/unit types, query prices,
 * payment methods, request statuses, order statuses, service groups.
 */
class AllReferenceEndpointsTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_license_types_endpoint(): void
    {
        RefDriverLicenseType::create(['title' => 'CDL-A', 'slug' => 'cdl-a', 'is_published' => 1]);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/license-types')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'CDL-A');
    }

    public function test_license_endorsements_endpoint(): void
    {
        RefDriverLicenseEndrs::create(['title' => 'Hazmat', 'slug' => 'hazmat', 'is_published' => 1]);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/license-endorsements')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Hazmat');
    }

    public function test_vehicle_ownership_types_endpoint(): void
    {
        RefVehicleOwnershipType::create(['name' => 'Owned', 'code' => 'owned']);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/vehicle-ownership-types')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Owned');
    }

    public function test_vehicle_unit_types_endpoint(): void
    {
        RefVehicleUnitType::create(['name' => 'Tractor', 'code' => 'tractor']);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/vehicle-unit-types')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Tractor');
    }

    public function test_query_prices_endpoint(): void
    {
        RefQueryPrice::create(['type' => 'limited', 'price_per_query' => 4.5]);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/query-prices')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_payment_methods_endpoint(): void
    {
        RefPaymentMethod::create(['name' => 'Card', 'code' => 'card']);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/payment-methods')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Card');
    }

    public function test_request_statuses_endpoint(): void
    {
        RefRequestStatus::create(['name' => 'New', 'slug' => 'new', 'color' => '#00f']);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/request-statuses')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'New');
    }

    public function test_order_statuses_endpoint(): void
    {
        RefOrderStatus::create(['name' => 'Pending', 'code' => 'pending']);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/order-statuses')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Pending');
    }

    public function test_service_groups_endpoint(): void
    {
        RefServiceGroup::create(['name' => 'Driver', 'slug' => 'driver']);
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/references/service-groups')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Driver');
    }
}
