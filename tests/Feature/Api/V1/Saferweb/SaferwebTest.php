<?php

namespace Tests\Feature\Api\V1\Saferweb;

use App\Models\UserCompany;
use App\Models\VehicleCrashSaferweb;
use App\Models\VehicleInspectionSaferweb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class SaferwebTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    protected function setUp(): void
    {
        parent::setUp();

        // Belt-and-suspenders: the read endpoints never call out, but guard anyway.
        Http::fake(['*' => Http::response([], 200)]);
    }

    private function makeInspection(array $overrides = []): VehicleInspectionSaferweb
    {
        return VehicleInspectionSaferweb::create(array_merge([
            'company_id' => null,
            'dot_number' => null,
            'unit_vin' => '1VIN',
            'unique_id' => 'U1',
            'report_number' => 'R-1',
            'report_sequence_number' => '1',
            'report_date' => '2025-01-01',
            'inspection_level' => 'I',
            'report_state' => 'CA',
            'report_state_id' => null,
            'api_data' => [],
        ], $overrides));
    }

    private function makeCrash(array $overrides = []): VehicleCrashSaferweb
    {
        return VehicleCrashSaferweb::create(array_merge([
            'company_id' => null,
            'dot_number' => null,
            'unit_vin' => '1VIN',
            'report_number' => 'C-1',
            'report_sequence_number' => '1',
            'report_date' => '2025-02-01',
            'report_state' => 'CA',
            'report_state_id' => null,
            'total_injuries' => 1,
            'total_fatalities' => 0,
            'api_data' => [],
        ], $overrides));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/saferweb/inspections')->assertStatus(401);
        $this->getJson('/api/v1/saferweb/crashes')->assertStatus(401);
    }

    public function test_inactive_blocked(): void
    {
        $user = $this->makeUserWithRole('company', ['is_active' => false]);
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/saferweb/inspections')->assertStatus(409);
    }

    public function test_driver_role_allowed(): void
    {
        $user = $this->makeUserWithRole('driver');
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/saferweb/inspections')->assertOk();
    }

    public function test_wrong_role_blocked(): void
    {
        $user = $this->makeUserWithRole('manager');
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/saferweb/inspections')->assertStatus(403);
    }

    public function test_company_lists_own_inspections(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $company = UserCompany::create(['user_id' => $user->id, 'name' => 'My Co', 'dot_number' => '111111']);

        $this->makeInspection(['company_id' => $company->id, 'dot_number' => $company->dot_number]);

        $this->getJson('/api/v1/saferweb/inspections')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_company_lists_own_crashes(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $company = UserCompany::create(['user_id' => $user->id, 'name' => 'My Co', 'dot_number' => '111111']);

        $this->makeCrash(['company_id' => $company->id, 'dot_number' => $company->dot_number]);

        $this->getJson('/api/v1/saferweb/crashes')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_company_views_own_inspection_detail(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $company = UserCompany::create(['user_id' => $user->id, 'name' => 'My Co', 'dot_number' => '111111']);

        $inspection = $this->makeInspection(['company_id' => $company->id, 'dot_number' => $company->dot_number]);

        $this->getJson('/api/v1/saferweb/inspections/'.$inspection->id)
            ->assertOk()
            ->assertJsonPath('data.id', $inspection->id);
    }

    public function test_company_views_own_crash_detail(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $company = UserCompany::create(['user_id' => $user->id, 'name' => 'My Co', 'dot_number' => '111111']);

        $crash = $this->makeCrash(['company_id' => $company->id, 'dot_number' => $company->dot_number]);

        $this->getJson('/api/v1/saferweb/crashes/'.$crash->id)
            ->assertOk()
            ->assertJsonPath('data.id', $crash->id);
    }

    public function test_404_foreign_inspection(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        UserCompany::create(['user_id' => $user->id, 'name' => 'My Co', 'dot_number' => '111111']);

        $foreign = $this->makeInspection(['company_id' => 999999, 'dot_number' => '999999']);

        $this->getJson('/api/v1/saferweb/inspections/'.$foreign->id)->assertStatus(404);
    }

    public function test_404_foreign_crash(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        UserCompany::create(['user_id' => $user->id, 'name' => 'My Co', 'dot_number' => '111111']);

        $foreign = $this->makeCrash(['company_id' => 999999, 'dot_number' => '999999']);

        $this->getJson('/api/v1/saferweb/crashes/'.$foreign->id)->assertStatus(404);
    }

    public function test_empty_when_no_matching_company(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        UserCompany::create(['user_id' => $user->id, 'name' => 'My Co', 'dot_number' => '111111']);

        // Rows belong to a different company / dot_number.
        $this->makeInspection(['company_id' => 999999, 'dot_number' => '999999']);

        $this->getJson('/api/v1/saferweb/inspections')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }
}
