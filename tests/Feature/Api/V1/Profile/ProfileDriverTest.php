<?php

namespace Tests\Feature\Api\V1\Profile;

use App\Models\Driver;
use App\Models\DriverAddress;
use App\Models\DriverLicense;
use App\Models\RefCountryStates;
use App\Models\RefDriverType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class ProfileDriverTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    private function makeDriverUser(): array
    {
        $user = $this->makeUserWithRole('driver');
        $driver = new Driver(['user_id' => $user->id]);
        $driver->saveQuietly();

        return [$user->fresh(), $driver];
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/profile/driver')->assertStatus(401);
    }

    public function test_non_driver_cannot_update_driver_profile(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->putJson('/api/v1/profile/driver', ['employment' => ['ssn' => '123']])
            ->assertStatus(403);
    }

    public function test_driver_loads_existing_file(): void
    {
        [$user, $driver] = $this->makeDriverUser();
        DriverLicense::create(['driver_id' => $driver->id, 'license_number' => 'DL-9000']);
        // Address is keyed by item_id, not driver_id.
        DriverAddress::create(['item_id' => $driver->id, 'city' => 'Dallas', 'zip' => '75001']);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/profile/driver')
            ->assertOk()
            ->assertJsonPath('data.driver_id', $driver->id)
            ->assertJsonPath('data.license.license_number', 'DL-9000')
            ->assertJsonPath('data.address.city', 'Dallas');
    }

    public function test_driver_updates_every_section(): void
    {
        [$user, $driver] = $this->makeDriverUser();
        $type = RefDriverType::create(['title' => 'Company driver', 'slug' => 'company', 'is_published' => true]);
        $state = RefCountryStates::create(['name' => 'Texas', 'code' => 'TX']);

        Sanctum::actingAs($user);

        $payload = [
            'employment' => ['dob' => '1990-04-12', 'ssn' => '111-22-3333', 'hire_date' => '2024-01-15', 'driver_type_id' => $type->id],
            'license'    => ['license_number' => 'DL-123', 'expiration_date' => '2030-01-01', 'state_id' => $state->id],
            'cdl'        => ['license_number' => 'CDL-77', 'expiration_date' => '2029-06-01'],
            'medical'    => ['examiner_name' => 'Dr. House', 'national_registry' => 'NR-55', 'issue_date' => '2025-01-01', 'expiration_date' => '2027-01-01'],
            'drug_test'  => ['test_type' => 'urine', 'test_date' => '2025-03-01', 'result' => 'negative'],
            'mvr'        => ['mvr_number' => 'MVR-1', 'mvr_date' => '2025-02-01'],
            'address'    => ['address1' => '1 Main St', 'city' => 'Dallas', 'state_id' => $state->id, 'zip' => '75001'],
        ];

        $this->putJson('/api/v1/profile/driver', $payload)
            ->assertOk()
            ->assertJsonPath('data.employment.ssn', '111-22-3333')
            ->assertJsonPath('data.license.license_number', 'DL-123')
            ->assertJsonPath('data.cdl.license_number', 'CDL-77')
            ->assertJsonPath('data.medical.examiner_name', 'Dr. House')
            ->assertJsonPath('data.drug_test.result', 'negative')
            ->assertJsonPath('data.mvr.mvr_number', 'MVR-1')
            ->assertJsonPath('data.address.address1', '1 Main St');

        $this->assertDatabaseHas('drivers', ['id' => $driver->id, 'ssn' => '111-22-3333', 'driver_type_id' => $type->id]);
        $this->assertDatabaseHas('driver_license', ['driver_id' => $driver->id, 'license_number' => 'DL-123', 'state_id' => $state->id]);
        $this->assertDatabaseHas('driver_cdl_license', ['driver_id' => $driver->id, 'license_number' => 'CDL-77']);
        $this->assertDatabaseHas('driver_medical_card', ['driver_id' => $driver->id, 'examiner_name' => 'Dr. House']);
        $this->assertDatabaseHas('driver_drug_test', ['driver_id' => $driver->id, 'result' => 'negative']);
        $this->assertDatabaseHas('driver_mvr', ['driver_id' => $driver->id, 'mvr_number' => 'MVR-1']);
        // Address persisted under item_id, NOT driver_id.
        $this->assertDatabaseHas('driver_address', ['item_id' => $driver->id, 'address1' => '1 Main St']);
    }

    public function test_updating_an_existing_sub_entity_replaces_not_duplicates(): void
    {
        [$user, $driver] = $this->makeDriverUser();
        DriverLicense::create(['driver_id' => $driver->id, 'license_number' => 'DL-100']);

        Sanctum::actingAs($user);

        $this->putJson('/api/v1/profile/driver', ['license' => ['license_number' => 'DL-200']])
            ->assertOk()
            ->assertJsonPath('data.license.license_number', 'DL-200');

        // The single existing row is updated in place, not duplicated.
        $this->assertSame(1, DriverLicense::where('driver_id', $driver->id)->count());
        $this->assertDatabaseHas('driver_license', ['driver_id' => $driver->id, 'license_number' => 'DL-200']);
        $this->assertDatabaseMissing('driver_license', ['driver_id' => $driver->id, 'license_number' => 'DL-100']);
    }

    public function test_empty_sections_do_not_create_rows(): void
    {
        [$user, $driver] = $this->makeDriverUser();
        Sanctum::actingAs($user);

        // Only employment carries values; the rest are blank and must not create rows.
        $this->putJson('/api/v1/profile/driver', [
            'employment' => ['ssn' => '999-00-0000'],
            'license'    => ['license_number' => null, 'state_id' => null],
            'medical'    => ['examiner_name' => ''],
        ])->assertOk();

        $this->assertDatabaseHas('drivers', ['id' => $driver->id, 'ssn' => '999-00-0000']);
        $this->assertDatabaseMissing('driver_license', ['driver_id' => $driver->id]);
        $this->assertDatabaseMissing('driver_medical_card', ['driver_id' => $driver->id]);
    }
}
