<?php

namespace Tests\Feature;

use App\Services\User\UserService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Tests\Feature\Traits\RoleFixtures;
use Tests\TestCase;

/**
 * Locks in the step-17 cutover contract: the Vue SPA owns '/', the legacy
 * Metronic/Breeze web routes are gone, the API + infrastructure paths are NOT
 * swallowed by the catch-all, and the auth emails point at the SPA.
 */
class CutoverTest extends TestCase
{
    use RefreshDatabase;
    use RoleFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        // The SPA shell renders @vite(...); stub it so the render assertions
        // don't depend on a built manifest (public/build is gitignored / not
        // built in CI).
        $this->withoutVite();
    }

    public function test_root_serves_spa_shell(): void
    {
        $this->get('/')->assertOk()->assertSee('id="app"', false);
    }

    public function test_arbitrary_spa_paths_serve_shell(): void
    {
        // Old dashboard URL + a deep auth link both fall through to the SPA shell.
        $this->get('/dashboard/my-drivers')->assertOk()->assertSee('id="app"', false);
        $this->get('/reset-password/sometoken')->assertOk()->assertSee('id="app"', false);
    }

    public function test_api_paths_not_swallowed(): void
    {
        $this->getJson('/api/v1/ping')->assertOk()->assertJson(['ok' => true]);
    }

    public function test_sanctum_csrf_not_swallowed(): void
    {
        $this->get('/sanctum/csrf-cookie')->assertNoContent();
    }

    public function test_health_route_not_swallowed(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_file_routes_still_registered(): void
    {
        $this->assertTrue(Route::has('file.download'));
        $this->assertTrue(Route::has('file.show'));
    }

    public function test_spa_catch_all_is_named(): void
    {
        $this->assertTrue(Route::has('spa'));
    }

    public function test_legacy_web_route_names_removed(): void
    {
        // These BARE web names lived only in the now-unrouted legacy files.
        $this->assertFalse(Route::has('dashboard'), 'web dashboard stub should be gone');
        $this->assertFalse(Route::has('web.index'), 'user.php landing route should be gone');
        $this->assertFalse(Route::has('password.reset'), 'bare web password.reset should be gone');
        $this->assertFalse(Route::has('verification.verify'), 'bare web verification.verify should be gone');
    }

    public function test_post_to_unknown_path_is_405_not_html(): void
    {
        // The catch-all is GET/HEAD only, so a stray POST must 405, never serve HTML.
        $this->post('/totally-unknown-path')->assertStatus(405);
    }

    public function test_password_reset_email_links_to_spa(): void
    {
        Notification::fake();
        $user = $this->makeUserWithRole('company');

        Password::sendResetLink(['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl ?? '';
            return str_contains($url, '/reset-password/') && ! str_contains($url, '/app/');
        });
    }

    public function test_one_time_login_link_targets_spa(): void
    {
        $user = $this->makeUserWithRole('driver');

        $link = app(UserService::class)->makeLoginLink($user->id);

        $this->assertStringContainsString('/login-onetime/', $link);
        $this->assertStringNotContainsString('/app/', $link);
    }
}
