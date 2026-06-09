<?php

namespace Tests\Feature\Api\V1\Vehicles;

use App\Models\UserCompany;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class VehicleCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/vehicles')->assertStatus(401);
    }

    public function test_company_user_lists_only_own_vehicles(): void
    {
        $a = $this->makeUserWithRole('company');
        $ac = UserCompany::create(['user_id' => $a->id, 'name' => 'A']);
        Vehicle::create(['number' => 'A1', 'company_id' => $ac->id, 'company_user_id' => $a->id]);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        Vehicle::create(['number' => 'B1', 'company_id' => $bc->id, 'company_user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->getJson('/api/v1/vehicles')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.number', 'A1');
    }

    public function test_store_creates_for_current_company(): void
    {
        $u = $this->makeUserWithRole('company');
        $c = UserCompany::create(['user_id' => $u->id, 'name' => 'Acme']);
        Sanctum::actingAs($u);

        $this->postJson('/api/v1/vehicles', [
            'number' => 'TRK-101', 'vin' => '1HGBH41JXMN109186',
        ])->assertStatus(201)->assertJsonPath('data.number', 'TRK-101');

        $this->assertDatabaseHas('vehicles', ['number' => 'TRK-101', 'company_id' => $c->id]);
    }

    public function test_store_requires_number(): void
    {
        $u = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $u->id, 'name' => 'Acme']);
        Sanctum::actingAs($u);
        $this->postJson('/api/v1/vehicles', [])
            ->assertStatus(422)->assertJsonValidationErrors('number');
    }

    public function test_show_404_for_foreign_vehicle(): void
    {
        $a = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $a->id, 'name' => 'A']);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        $foreign = Vehicle::create(['number' => 'B1', 'company_id' => $bc->id, 'company_user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->getJson('/api/v1/vehicles/'.$foreign->id)->assertStatus(404);
    }

    public function test_show_returns_own_vehicle(): void
    {
        $u = $this->makeUserWithRole('company');
        $c = UserCompany::create(['user_id' => $u->id, 'name' => 'Acme']);
        $v = Vehicle::create(['number' => 'MINE-1', 'company_id' => $c->id, 'company_user_id' => $u->id]);

        Sanctum::actingAs($u);
        $this->getJson('/api/v1/vehicles/'.$v->id)
            ->assertOk()
            ->assertJsonPath('data.id', $v->id)
            ->assertJsonPath('data.number', 'MINE-1');
    }

    public function test_update_404_for_foreign_vehicle(): void
    {
        $a = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $a->id, 'name' => 'A']);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        $foreign = Vehicle::create(['number' => 'B1', 'company_id' => $bc->id, 'company_user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->putJson('/api/v1/vehicles/'.$foreign->id, ['number' => 'HACKED'])
            ->assertStatus(404);
    }

    public function test_destroy_404_for_foreign_vehicle(): void
    {
        $a = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $a->id, 'name' => 'A']);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        $foreign = Vehicle::create(['number' => 'B1', 'company_id' => $bc->id, 'company_user_id' => $b->id]);

        Sanctum::actingAs($a);
        $this->deleteJson('/api/v1/vehicles/'.$foreign->id)->assertStatus(404);
        $this->assertDatabaseHas('vehicles', ['id' => $foreign->id]);
    }

    public function test_update_persists(): void
    {
        $u = $this->makeUserWithRole('company');
        $c = UserCompany::create(['user_id' => $u->id, 'name' => 'Acme']);
        $v = Vehicle::create(['number' => 'OLD', 'company_id' => $c->id, 'company_user_id' => $u->id]);
        Sanctum::actingAs($u);

        $this->putJson('/api/v1/vehicles/'.$v->id, ['number' => 'NEW', 'vin' => 'WBA00000000000000'])
            ->assertOk()->assertJsonPath('data.number', 'NEW');
        $this->assertDatabaseHas('vehicles', ['id' => $v->id, 'number' => 'NEW']);
    }

    public function test_destroy(): void
    {
        $u = $this->makeUserWithRole('company');
        $c = UserCompany::create(['user_id' => $u->id, 'name' => 'Acme']);
        $v = Vehicle::create(['number' => 'X', 'company_id' => $c->id, 'company_user_id' => $u->id]);
        Sanctum::actingAs($u);
        $this->deleteJson('/api/v1/vehicles/'.$v->id)->assertNoContent();
        $this->assertDatabaseMissing('vehicles', ['id' => $v->id]);
    }

    public function test_admin_sees_owner_and_can_filter_by_user(): void
    {
        $a = $this->makeUserWithRole('company', ['email' => 'owner-a@example.com']);
        $ac = UserCompany::create(['user_id' => $a->id, 'name' => 'A']);
        Vehicle::create(['number' => 'A1', 'company_id' => $ac->id, 'company_user_id' => $a->id]);

        $b = $this->makeUserWithRole('company');
        $bc = UserCompany::create(['user_id' => $b->id, 'name' => 'B']);
        Vehicle::create(['number' => 'B1', 'company_id' => $bc->id, 'company_user_id' => $b->id]);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/vehicles')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/v1/vehicles?user_id='.$a->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.number', 'A1')
            ->assertJsonPath('data.0.owner.email', 'owner-a@example.com');
    }
}
