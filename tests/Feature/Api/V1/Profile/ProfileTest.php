<?php

namespace Tests\Feature\Api\V1\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class ProfileTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/profile')->assertStatus(401);
    }

    public function test_admin_blocked_by_role(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));
        $this->getJson('/api/v1/profile')->assertStatus(403);
    }

    public function test_inactive_blocked_with_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company', ['is_active' => false, 'reg_step' => '2']));
        $this->getJson('/api/v1/profile')
            ->assertStatus(409)
            ->assertJson(['reg_step' => '2']);
    }

    public function test_show_returns_profile(): void
    {
        $user = $this->makeUserWithRole('company', [
            'firstname' => 'Jane', 'lastname' => 'Doe',
            'email' => 'jane@example.com',
        ]);
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.firstname', 'Jane')
            ->assertJsonPath('data.email', 'jane@example.com')
            ->assertJsonStructure(['data' => ['id','firstname','lastname','email','phone','birthday','address','company']]);
    }

    public function test_update_persists_fields(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/profile', [
            'firstname' => 'Updated',
            'lastname'  => 'Name',
            'phone'     => '5551234567',
            'birthday'  => '1990-05-12',
        ])->assertOk()
            ->assertJsonPath('data.firstname', 'Updated')
            ->assertJsonPath('data.birthday', '1990-05-12');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'Updated',
            'phone' => '5551234567',
        ]);

        // birthday must survive the round-trip in the response, not just the DB.
        $this->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.birthday', '1990-05-12');
    }

    public function test_update_rejects_duplicate_email(): void
    {
        $other = $this->makeUserWithRole('company', ['email' => 'taken@example.com']);
        $user  = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/profile', ['email' => 'taken@example.com'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_password_update_requires_current(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/profile/password', [
            'current_password' => 'WRONG',
            'password' => 'BrandNewPass123!',
            'password_confirmation' => 'BrandNewPass123!',
        ])->assertStatus(422)->assertJsonValidationErrors('current_password');
    }

    public function test_password_update_succeeds(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/profile/password', [
            'current_password' => 'password',
            'password' => 'BrandNewPass123!',
            'password_confirmation' => 'BrandNewPass123!',
        ])->assertNoContent();

        $this->assertTrue(Hash::check('BrandNewPass123!', $user->fresh()->password));
    }

    public function test_address_upsert_creates_then_updates(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/profile/address', [
            'address1' => '123 Main St',
            'city'     => 'Anywhere',
            'zip'      => '12345',
        ])->assertSuccessful()->assertJsonPath('data.zip', '12345');

        $this->assertDatabaseHas('user_address', ['user_id' => $user->id, 'zip' => '12345']);

        $this->putJson('/api/v1/profile/address', [
            'address1' => '456 Other St',
            'zip'      => '67890',
        ])->assertOk()->assertJsonPath('data.zip', '67890');

        $this->assertDatabaseHas('user_address', ['user_id' => $user->id, 'zip' => '67890']);
        $this->assertEquals(1, \DB::table('user_address')->where('user_id', $user->id)->count());
    }

    public function test_address_requires_zip(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->putJson('/api/v1/profile/address', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('zip');
    }

    public function test_company_show_returns_null_when_absent(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));
        $this->getJson('/api/v1/profile/company')
            ->assertOk()
            ->assertJson(['data' => null]);
    }

    public function test_company_upsert_creates_with_addresses(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/profile/company', [
            'name' => 'Acme Trucking',
            'phone' => '5550001111',
            'dot_number' => '1234567',
            'trucks_number' => 5,
            'drivers_number' => 3,
            'business' => ['address1' => '1 Biz Ave', 'city' => 'Big City', 'zip' => '11111'],
            'mailing'  => ['address1' => '2 Mail Rd', 'city' => 'Big City', 'zip' => '22222'],
        ])->assertOk()->assertJsonPath('data.name', 'Acme Trucking');

        $this->assertDatabaseHas('user_company', ['user_id' => $user->id, 'name' => 'Acme Trucking']);
        $this->assertDatabaseHas('user_company_address', ['type' => 'business', 'zip' => '11111']);
        $this->assertDatabaseHas('user_company_address', ['type' => 'mailing', 'zip' => '22222']);
    }
}
