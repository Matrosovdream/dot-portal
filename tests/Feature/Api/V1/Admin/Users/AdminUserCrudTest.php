<?php

namespace Tests\Feature\Api\V1\Admin\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class AdminUserCrudTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    private function makeTargetUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'firstname' => 'Target',
            'lastname'  => 'User',
            'email'     => 'target' . uniqid() . '@example.com',
            'phone'     => '+15551234567',
            'birthday'  => '1990-01-01',
            'password'  => 'secret-password',
            'is_active' => true,
        ], $overrides));
    }

    public function test_guest_blocked(): void
    {
        $this->getJson('/api/v1/admin/users')->assertStatus(401);
    }

    public function test_manager_forbidden_403(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('manager'));

        $this->getJson('/api/v1/admin/users')->assertStatus(403);
    }

    public function test_company_forbidden_403(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->getJson('/api/v1/admin/users')->assertStatus(403);
    }

    public function test_inactive_admin_blocked_409(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin', ['is_active' => false]));

        $this->getJson('/api/v1/admin/users')->assertStatus(409);
    }

    public function test_admin_can_index(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $target = $this->makeTargetUser(['email' => 'indexed@example.com']);

        $this->getJson('/api/v1/admin/users')
            ->assertOk()
            ->assertJsonPath('data.0.email', $target->email);
    }

    public function test_admin_can_store_with_role(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $payload = [
            'firstname' => 'New',
            'lastname'  => 'Member',
            'email'     => 'new.member@example.com',
            'phone'     => '+15550001111',
            'birthday'  => '1985-06-15',
            'password'  => 'password123',
            'role'      => 'driver',
        ];

        $response = $this->postJson('/api/v1/admin/users', $payload)
            ->assertStatus(201)
            ->assertJsonPath('data.email', 'new.member@example.com')
            ->assertJsonPath('data.firstname', 'New')
            ->assertJsonPath('data.role.slug', 'driver');

        $this->assertDatabaseHas('users', [
            'email'     => 'new.member@example.com',
            'firstname' => 'New',
            'fullname'  => 'New Member',
        ]);

        $userId = $response->json('data.id');
        $roleId = Role::where('slug', 'driver')->value('id');

        $this->assertDatabaseHas('user_roles', [
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    public function test_store_password_is_hashed_not_plaintext(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->postJson('/api/v1/admin/users', [
            'firstname' => 'Hash',
            'email'     => 'hash.check@example.com',
            'password'  => 'password123',
            'role'      => 'driver',
        ])->assertStatus(201);

        $stored = User::where('email', 'hash.check@example.com')->value('password');
        $this->assertNotSame('password123', $stored);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password123', $stored));
    }

    public function test_admin_can_show(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $target = $this->makeTargetUser();

        $this->getJson("/api/v1/admin/users/{$target->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $target->id)
            ->assertJsonPath('data.email', $target->email);
    }

    public function test_show_missing_user_404(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->getJson('/api/v1/admin/users/999999')->assertStatus(404);
    }

    public function test_admin_can_update(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $target = $this->makeTargetUser();

        $this->putJson("/api/v1/admin/users/{$target->id}", [
            'firstname' => 'Renamed',
            'role'      => 'company',
        ])
            ->assertOk()
            ->assertJsonPath('data.firstname', 'Renamed')
            ->assertJsonPath('data.role.slug', 'company');

        $this->assertDatabaseHas('users', [
            'id'        => $target->id,
            'firstname' => 'Renamed',
        ]);

        $roleId = Role::where('slug', 'company')->value('id');
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $target->id,
            'role_id' => $roleId,
        ]);
    }

    public function test_update_keeps_own_email_ok(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $target = $this->makeTargetUser(['email' => 'keepmine@example.com']);

        $this->putJson("/api/v1/admin/users/{$target->id}", [
            'email'    => 'keepmine@example.com',
            'lastname' => 'Updated',
        ])->assertOk();
    }

    public function test_admin_can_destroy(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $target = $this->makeTargetUser();
        $target->setRole('driver');

        $this->deleteJson("/api/v1/admin/users/{$target->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
        $this->assertDatabaseMissing('user_roles', ['user_id' => $target->id]);
    }

    public function test_store_duplicate_email_422(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->makeTargetUser(['email' => 'dupe@example.com']);

        $this->postJson('/api/v1/admin/users', [
            'firstname' => 'Dup',
            'email'     => 'dupe@example.com',
            'password'  => 'password123',
            'role'      => 'driver',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_store_bad_role_422(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('admin'));

        $this->postJson('/api/v1/admin/users', [
            'firstname' => 'Bad',
            'email'     => 'bad.role@example.com',
            'password'  => 'password123',
            'role'      => 'nonexistent-role',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('role');
    }
}
