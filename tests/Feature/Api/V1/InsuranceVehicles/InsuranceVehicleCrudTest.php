<?php

namespace Tests\Feature\Api\V1\InsuranceVehicles;

use App\Models\InsuranceVehicle;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class InsuranceVehicleCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/insurance-vehicles')->assertStatus(401);
    }

    public function test_company_user_lists_only_own(): void
    {
        $a = $this->makeUserWithRole('company');
        $ac = UserCompany::create(['user_id' => $a->id, 'name' => 'A']);
        InsuranceVehicle::create(['name' => 'A Ins', 'company_id' => $ac->id, 'user_id' => $a->id]);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        InsuranceVehicle::create(['name' => 'B Ins', 'company_id' => $bc->id, 'user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->getJson('/api/v1/insurance-vehicles')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'A Ins');
    }

    public function test_store_creates(): void
    {
        $u = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $u->id, 'name' => 'C']);
        Sanctum::actingAs($u);

        $this->postJson('/api/v1/insurance-vehicles', [
            'name' => 'StateFarm Policy',
            'number' => 'POL-001',
            'start_date' => '2024-01-01',
            'end_date' => '2025-01-01',
        ])->assertStatus(201)->assertJsonPath('data.name', 'StateFarm Policy');

        $this->assertDatabaseHas('insurances_vehicle', ['name' => 'StateFarm Policy', 'number' => 'POL-001']);
    }

    public function test_store_requires_name(): void
    {
        $u = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $u->id, 'name' => 'C']);
        Sanctum::actingAs($u);
        $this->postJson('/api/v1/insurance-vehicles', [])
            ->assertStatus(422)->assertJsonValidationErrors('name');
    }

    public function test_end_date_must_be_after_start(): void
    {
        $u = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $u->id, 'name' => 'C']);
        Sanctum::actingAs($u);
        $this->postJson('/api/v1/insurance-vehicles', [
            'name' => 'X',
            'start_date' => '2024-12-01',
            'end_date' => '2024-01-01',
        ])->assertStatus(422)->assertJsonValidationErrors('end_date');
    }

    public function test_show_returns_own_record(): void
    {
        $u = $this->makeUserWithRole('company');
        $c = UserCompany::create(['user_id' => $u->id, 'name' => 'C']);
        $rec = InsuranceVehicle::create([
            'name' => 'My Policy', 'number' => 'POL-9',
            'company_id' => $c->id, 'user_id' => $u->id,
        ]);

        Sanctum::actingAs($u);
        $this->getJson('/api/v1/insurance-vehicles/'.$rec->id)
            ->assertOk()
            ->assertJsonPath('data.id', $rec->id)
            ->assertJsonPath('data.name', 'My Policy')
            ->assertJsonPath('data.number', 'POL-9');
    }

    public function test_show_404_for_foreign_record(): void
    {
        $a = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $a->id, 'name' => 'A']);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        $foreign = InsuranceVehicle::create(['name' => 'B Ins', 'company_id' => $bc->id, 'user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->getJson('/api/v1/insurance-vehicles/'.$foreign->id)->assertStatus(404);
    }

    public function test_update_404_for_foreign_record(): void
    {
        $a = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $a->id, 'name' => 'A']);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        $foreign = InsuranceVehicle::create(['name' => 'B Ins', 'company_id' => $bc->id, 'user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->putJson('/api/v1/insurance-vehicles/'.$foreign->id, ['name' => 'Hacked'])
            ->assertStatus(404);
    }

    public function test_destroy_404_for_foreign_record(): void
    {
        $a = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $a->id, 'name' => 'A']);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        $foreign = InsuranceVehicle::create(['name' => 'B Ins', 'company_id' => $bc->id, 'user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->deleteJson('/api/v1/insurance-vehicles/'.$foreign->id)->assertStatus(404);
        $this->assertDatabaseHas('insurances_vehicle', ['id' => $foreign->id]);
    }

    public function test_update_and_destroy(): void
    {
        $u = $this->makeUserWithRole('company');
        $c = UserCompany::create(['user_id' => $u->id, 'name' => 'C']);
        $rec = InsuranceVehicle::create(['name' => 'Old', 'company_id' => $c->id, 'user_id' => $u->id]);

        Sanctum::actingAs($u);
        $this->putJson('/api/v1/insurance-vehicles/'.$rec->id, ['name' => 'New'])
            ->assertOk()->assertJsonPath('data.name', 'New');

        $this->deleteJson('/api/v1/insurance-vehicles/'.$rec->id)->assertNoContent();
        $this->assertDatabaseMissing('insurances_vehicle', ['id' => $rec->id]);
    }

    public function test_admin_sees_owner_and_can_filter_by_user(): void
    {
        $a = $this->makeUserWithRole('company', ['email' => 'owner-a@example.com']);
        $ac = UserCompany::create(['user_id' => $a->id, 'name' => 'A']);
        InsuranceVehicle::create(['name' => 'A Ins', 'company_id' => $ac->id, 'user_id' => $a->id]);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        InsuranceVehicle::create(['name' => 'B Ins', 'company_id' => $bc->id, 'user_id' => $b->id]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/insurance-vehicles')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/v1/insurance-vehicles?user_id='.$a->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'A Ins')
            ->assertJsonPath('data.0.owner.email', 'owner-a@example.com');
    }
}
