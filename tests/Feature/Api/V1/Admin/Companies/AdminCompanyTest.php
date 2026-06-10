<?php

namespace Tests\Feature\Api\V1\Admin\Companies;

use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class AdminCompanyTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    /** Create a company-role owner + its user_company profile. */
    private function makeCompany(array $attrs = []): UserCompany
    {
        $owner = $this->makeUserWithRole('company');

        return UserCompany::create(array_merge([
            'user_id' => $owner->id,
            'name'    => 'Acme Trucking',
        ], $attrs));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/companies')->assertStatus(401);
    }

    public function test_company_role_forbidden(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/admin/companies')->assertStatus(403);
    }

    public function test_driver_forbidden(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('driver'));
        $this->getJson('/api/v1/admin/companies')->assertStatus(403);
    }

    public function test_admin_lists_with_owner_shape(): void
    {
        $owner = $this->makeUserWithRole('company', ['email' => 'owner@example.com']);
        UserCompany::create(['user_id' => $owner->id, 'name' => 'Acme', 'dot_number' => 'DOT123']);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/companies')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Acme')
            ->assertJsonPath('data.0.owner.email', 'owner@example.com')
            ->assertJsonPath('data.0.is_active', true);
    }

    public function test_manager_can_list(): void
    {
        $this->makeCompany();
        Sanctum::actingAs($this->makeUserWithRole('manager'));
        $this->getJson('/api/v1/admin/companies')->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_search_by_name_dot_mc(): void
    {
        $a = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $a->id, 'name' => 'Alpha Freight', 'dot_number' => 'D-1', 'mc_number' => 'M-9']);
        $b = $this->makeUserWithRole('company');
        UserCompany::create(['user_id' => $b->id, 'name' => 'Beta Lines', 'dot_number' => 'D-2', 'mc_number' => 'M-8']);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/companies?q=Alpha')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/admin/companies?q=M-8')->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Beta Lines');
    }

    public function test_admin_shows_single_company(): void
    {
        $owner = $this->makeUserWithRole('company', ['email' => 'owner@example.com']);
        $company = UserCompany::create(['user_id' => $owner->id, 'name' => 'Acme', 'dot_number' => 'DOT123']);

        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/companies/'.$company->id)
            ->assertOk()
            ->assertJsonPath('data.id', $company->id)
            ->assertJsonPath('data.name', 'Acme')
            ->assertJsonPath('data.dot_number', 'DOT123')
            ->assertJsonPath('data.owner.email', 'owner@example.com')
            ->assertJsonPath('data.is_active', true);
    }

    public function test_show_missing_company_404(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/admin/companies/999999')->assertStatus(404);
    }

    public function test_admin_can_create(): void
    {
        $owner = $this->makeUserWithRole('company', ['email' => 'newco@example.com']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->postJson('/api/v1/admin/companies', [
            'user_id'    => $owner->id,
            'name'       => 'Fresh Carrier',
            'dot_number' => 'DOT-77',
        ])->assertStatus(201)->assertJsonPath('data.name', 'Fresh Carrier');

        $this->assertDatabaseHas('user_company', ['user_id' => $owner->id, 'name' => 'Fresh Carrier']);
    }

    public function test_create_requires_name_and_owner(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->postJson('/api/v1/admin/companies', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'name']);
    }

    public function test_create_rejects_duplicate_owner(): void
    {
        $company = $this->makeCompany();
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->postJson('/api/v1/admin/companies', [
            'user_id' => $company->user_id,
            'name'    => 'Second Co',
        ])->assertStatus(422)->assertJsonValidationErrors('user_id');
    }

    public function test_admin_can_update(): void
    {
        $company = $this->makeCompany(['name' => 'Old Name']);
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->putJson('/api/v1/admin/companies/'.$company->id, [
            'name'       => 'New Name',
            'mc_number'  => 'MC-555',
        ])->assertOk()->assertJsonPath('data.name', 'New Name');

        $this->assertDatabaseHas('user_company', ['id' => $company->id, 'name' => 'New Name', 'mc_number' => 'MC-555']);
    }

    public function test_admin_can_delete(): void
    {
        $company = $this->makeCompany();
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->deleteJson('/api/v1/admin/companies/'.$company->id)->assertNoContent();
        $this->assertDatabaseMissing('user_company', ['id' => $company->id]);
    }
}
