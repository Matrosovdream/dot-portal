<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Traits\RoleFixtures;
use Tests\TestCase;

class FoundationTest extends TestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    public function test_ping_returns_json(): void
    {
        $this->getJson('/api/v1/ping')
            ->assertOk()
            ->assertJsonStructure(['ok', 'time'])
            ->assertJson(['ok' => true]);
    }

    public function test_csrf_cookie_endpoint_exists(): void
    {
        $this->get('/sanctum/csrf-cookie')->assertNoContent();
    }

    public function test_spa_serves_shell_at_root(): void
    {
        $this->withoutVite();
        $this->get('/')
            ->assertOk()
            ->assertSee('id="app"', false);
    }

    public function test_spa_catchall_serves_shell_for_deep_routes(): void
    {
        $this->withoutVite();
        $this->get('/drivers/123/profile')
            ->assertOk()
            ->assertSee('id="app"', false);
    }

    public function test_spa_catchall_does_not_swallow_api_paths(): void
    {
        // The catch-all must NOT serve the SPA shell for /api/* — these are JSON endpoints.
        $this->getJson('/api/v1/ping')
            ->assertOk()
            ->assertJsonStructure(['ok', 'time']);
    }

    public function test_has_role_middleware_returns_json_403_for_api(): void
    {
        // Register a temporary api route under the middleware to assert JSON 403.
        Route::middleware(['auth:sanctum', 'hasRole:admin'])
            ->get('/api/v1/_test/admin-only', fn () => response()->json(['ok' => true]));

        $user = $this->makeUserWithRole('driver');
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/_test/admin-only')
            ->assertStatus(403)
            ->assertJson(['message' => 'Forbidden.']);
    }

    public function test_has_role_middleware_passes_for_correct_role(): void
    {
        Route::middleware(['auth:sanctum', 'hasRole:admin'])
            ->get('/api/v1/_test/admin-only-ok', fn () => response()->json(['ok' => true]));

        $admin = $this->makeUserWithRole('admin');
        Sanctum::actingAs($admin);

        $this->getJson('/api/v1/_test/admin-only-ok')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    public function test_is_user_active_middleware_returns_409_when_inactive(): void
    {
        Route::middleware(['auth:sanctum', 'user.isActive'])
            ->get('/api/v1/_test/active', fn () => response()->json(['ok' => true]));

        $user = $this->makeUserWithRole('company', ['is_active' => false, 'reg_step' => '2']);
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/_test/active')
            ->assertStatus(409)
            ->assertJson(['reg_step' => '2']);
    }
}
