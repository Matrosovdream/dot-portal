<?php

namespace Tests\Feature\Api\V1\Drivers;

use App\Models\Driver;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class DriverCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/drivers')->assertStatus(401);
    }

    public function test_inactive_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false]));
        $this->getJson('/api/v1/drivers')->assertStatus(409);
    }

    public function test_company_user_lists_only_own_drivers(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'My Co']);

        $myDriverUser = User::factory()->create(['email' => 'mine@example.com']);
        Driver::create([
            'user_id' => $myDriverUser->id,
            'company_id' => $company->id,
            'company_user_id' => $companyUser->id,
            'status_id' => 1,
        ]);

        $otherCompanyUser = $this->makeUserWithRole('company');
        $otherCompany = UserCompany::create(['user_id' => $otherCompanyUser->id, 'name' => 'Other Co']);
        $otherDriverUser = User::factory()->create(['email' => 'theirs@example.com']);
        Driver::create([
            'user_id' => $otherDriverUser->id,
            'company_id' => $otherCompany->id,
            'company_user_id' => $otherCompanyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);

        $resp = $this->getJson('/api/v1/drivers')->assertOk();
        $resp->assertJsonCount(1, 'data')
             ->assertJsonPath('data.0.email', 'mine@example.com');
    }

    public function test_admin_sees_all_drivers(): void
    {
        $u1 = User::factory()->create(['email' => 'a@example.com']);
        Driver::create(['user_id' => $u1->id, 'status_id' => 1]);
        $u2 = User::factory()->create(['email' => 'b@example.com']);
        Driver::create(['user_id' => $u2->id, 'status_id' => 1]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/drivers')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_filter_by_status(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);

        foreach ([1, 1, 3] as $i => $status) {
            $u = User::factory()->create();
            Driver::create([
                'user_id' => $u->id,
                'company_id' => $company->id,
                'company_user_id' => $companyUser->id,
                'status_id' => $status,
            ]);
        }

        Sanctum::actingAs($companyUser);
        $this->getJson('/api/v1/drivers?status=terminated')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/drivers?status=active')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_search_by_q(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);

        $u1 = User::factory()->create(['firstname' => 'Alice', 'email' => 'alice@example.com']);
        Driver::create(['user_id' => $u1->id, 'company_id' => $company->id, 'company_user_id' => $companyUser->id, 'status_id' => 1]);
        $u2 = User::factory()->create(['firstname' => 'Bob', 'email' => 'bob@example.com']);
        Driver::create(['user_id' => $u2->id, 'company_id' => $company->id, 'company_user_id' => $companyUser->id, 'status_id' => 1]);

        Sanctum::actingAs($companyUser);
        $this->getJson('/api/v1/drivers?q=Alice')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.email', 'alice@example.com');
    }

    public function test_store_creates_user_and_driver(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);
        Sanctum::actingAs($companyUser);

        $payload = [
            'firstname' => 'Pat',
            'lastname'  => 'River',
            'phone'     => '5551234567',
            'email'     => 'pat@example.com',
            'password'  => 'Drivers12345!',
            'hire_date' => '2024-01-15',
        ];

        $this->postJson('/api/v1/drivers', $payload)
            ->assertStatus(201)
            ->assertJsonPath('data.firstname', 'Pat')
            ->assertJsonPath('data.email', 'pat@example.com');

        $this->assertDatabaseHas('users', ['email' => 'pat@example.com', 'firstname' => 'Pat']);
        $this->assertDatabaseHas('drivers', ['hire_date' => '2024-01-15']);
    }

    public function test_store_rejects_duplicate_email(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);
        Sanctum::actingAs($companyUser);
        User::factory()->create(['email' => 'taken@example.com']);

        $this->postJson('/api/v1/drivers', [
            'firstname' => 'X', 'lastname' => 'Y', 'phone' => '5550000',
            'email' => 'taken@example.com',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_show_404_for_foreign_driver(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);

        $otherCompanyUser = $this->makeUserWithRole('company');
        $otherCompany = UserCompany::create(['user_id' => $otherCompanyUser->id, 'name' => 'Other']);
        $u = User::factory()->create();
        $foreignDriver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $otherCompany->id,
            'company_user_id' => $otherCompanyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->getJson('/api/v1/drivers/'.$foreignDriver->id)->assertStatus(404);
    }

    public function test_show_returns_own_driver(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);
        $u = User::factory()->create(['firstname' => 'Mine', 'email' => 'mine@example.com']);
        $driver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $company->id,
            'company_user_id' => $companyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->getJson('/api/v1/drivers/'.$driver->id)
            ->assertOk()
            ->assertJsonPath('data.id', $driver->id)
            ->assertJsonPath('data.firstname', 'Mine');
    }

    public function test_update_404_for_foreign_driver(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);

        $otherCompanyUser = $this->makeUserWithRole('company');
        $otherCompany = UserCompany::create(['user_id' => $otherCompanyUser->id, 'name' => 'Other']);
        $u = User::factory()->create();
        $foreignDriver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $otherCompany->id,
            'company_user_id' => $otherCompanyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->putJson('/api/v1/drivers/'.$foreignDriver->id, ['firstname' => 'Hacked'])
            ->assertStatus(404);
    }

    public function test_destroy_404_for_foreign_driver(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);

        $otherCompanyUser = $this->makeUserWithRole('company');
        $otherCompany = UserCompany::create(['user_id' => $otherCompanyUser->id, 'name' => 'Other']);
        $u = User::factory()->create();
        $foreignDriver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $otherCompany->id,
            'company_user_id' => $otherCompanyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->deleteJson('/api/v1/drivers/'.$foreignDriver->id)->assertStatus(404);
        $this->assertDatabaseHas('drivers', ['id' => $foreignDriver->id]);
    }

    public function test_update_persists(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);
        $u = User::factory()->create(['firstname' => 'Old', 'email' => 'old@example.com']);
        $driver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $company->id,
            'company_user_id' => $companyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->putJson('/api/v1/drivers/'.$driver->id, [
            'firstname' => 'New',
            'lastname'  => 'Name',
            'email'     => 'new@example.com',
            'ssn'       => '123-45-6789',
        ])->assertOk()->assertJsonPath('data.firstname', 'New');

        $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'firstname' => 'New']);
        $this->assertDatabaseHas('drivers', ['id' => $driver->id, 'ssn' => '123-45-6789']);
    }

    public function test_destroy_removes_driver(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);
        $u = User::factory()->create();
        $driver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $company->id,
            'company_user_id' => $companyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->deleteJson('/api/v1/drivers/'.$driver->id)->assertNoContent();

        $this->assertDatabaseMissing('drivers', ['id' => $driver->id]);
    }

    public function test_terminate_sets_status_3(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);
        $u = User::factory()->create();
        $driver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $company->id,
            'company_user_id' => $companyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->postJson('/api/v1/drivers/'.$driver->id.'/terminate')
            ->assertOk()
            ->assertJsonPath('data.status_id', 3);
    }

    public function test_send_onetime_returns_ack(): void
    {
        $companyUser = $this->makeUserWithRole('company');
        $company = UserCompany::create(['user_id' => $companyUser->id, 'name' => 'C']);
        $u = User::factory()->create();
        $driver = Driver::create([
            'user_id' => $u->id,
            'company_id' => $company->id,
            'company_user_id' => $companyUser->id,
            'status_id' => 1,
        ]);

        Sanctum::actingAs($companyUser);
        $this->postJson('/api/v1/drivers/'.$driver->id.'/send-onetime')
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    public function test_admin_sees_owner_and_can_filter_by_user(): void
    {
        $companyA = $this->makeUserWithRole('company', ['email' => 'owner-a@example.com']);
        $coA = UserCompany::create(['user_id' => $companyA->id, 'name' => 'A']);
        Driver::create([
            'user_id' => User::factory()->create()->id,
            'company_id' => $coA->id, 'company_user_id' => $companyA->id, 'status_id' => 1,
        ]);

        $companyB = $this->makeUserWithRole('company');
        $coB = UserCompany::create(['user_id' => $companyB->id, 'name' => 'B']);
        Driver::create([
            'user_id' => User::factory()->create()->id,
            'company_id' => $coB->id, 'company_user_id' => $companyB->id, 'status_id' => 1,
        ]);

        // Admin: owner is exposed and the list can be narrowed to one owner account.
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/drivers')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/v1/drivers?user_id='.$companyA->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.owner.id', $companyA->id)
            ->assertJsonPath('data.0.owner.email', 'owner-a@example.com');

        // Company user: the user_id filter cannot widen their own scope.
        Sanctum::actingAs($companyB);
        $this->getJson('/api/v1/drivers?user_id='.$companyA->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.owner.id', $companyB->id);
    }
}
