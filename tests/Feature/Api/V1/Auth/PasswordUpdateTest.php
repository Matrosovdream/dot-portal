<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\V1\ApiTestCase;
use Tests\Feature\Traits\RoleFixtures;

class PasswordUpdateTest extends ApiTestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_guest_blocked(): void
    {
        $this->putJson('/api/v1/auth/password', [
            'current_password' => 'password',
            'password' => 'BrandNewPass123!',
            'password_confirmation' => 'BrandNewPass123!',
        ])->assertStatus(401);
    }

    public function test_update_requires_correct_current_password(): void
    {
        Sanctum::actingAs($this->makeUserWithRole('company'));

        $this->putJson('/api/v1/auth/password', [
            'current_password' => 'WRONG',
            'password' => 'BrandNewPass123!',
            'password_confirmation' => 'BrandNewPass123!',
        ])->assertStatus(422)->assertJsonValidationErrors('current_password');
    }

    public function test_update_succeeds(): void
    {
        $user = $this->makeUserWithRole('company');
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/auth/password', [
            'current_password' => 'password',
            'password' => 'BrandNewPass123!',
            'password_confirmation' => 'BrandNewPass123!',
        ])->assertNoContent();

        $this->assertTrue(Hash::check('BrandNewPass123!', $user->fresh()->password));
    }
}
