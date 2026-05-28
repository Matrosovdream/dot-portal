# Step 01 — Foundation

Set up Sanctum SPA auth, the `/api/v1` skeleton, the new `resources/spa/` Vite entry, the Sakai theme, and a smoke test that proves all three planes are wired.

## Backend

### 1.1 Install Sanctum
- `composer require laravel/sanctum`
- `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
- `php artisan migrate`
- `config/sanctum.php`: set `stateful` to include local + prod hosts (env-driven).

### 1.2 Register Sanctum stateful middleware
In `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();    // adds EnsureFrontendRequestsAreStateful to the api group
    $middleware->alias([
        'isAdmin' => isAdmin::class,
        'isUser'  => isUser::class,
        'hasRole' => hasRole::class,
        'user.isActive' => isUserActive::class,
    ]);
})
->withRouting(
    web:      __DIR__.'/../routes/web.php',
    api:      __DIR__.'/../routes/api.php',
    apiPrefix:'api',
    commands: __DIR__.'/../routes/console.php',
    health:   '/up',
)
```

### 1.3 Skeleton route file
Create `routes/api.php`:
```php
<?php
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/ping', fn () => response()->json(['ok' => true, 'time' => now()->toIso8601String()]))
        ->name('ping');
});
```

### 1.4 Folder scaffolding
Create empty folders so future steps just drop files in:
- `app/Http/Controllers/Api/V1/`
- `app/Http/Requests/Api/V1/`
- `app/Http/Resources/V1/`
- `tests/Feature/Api/V1/`

### 1.5 hasRole middleware tweak
The current `hasRole` redirects to `web.index` on failure. For API requests that returns HTML. Update it to:
```php
if (!auth()->user()->hasRole($roles)) {
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Forbidden.'], 403);
    }
    return redirect()->route('web.index');
}
```
Same treatment for `isUserActive` — JSON `403` (or `409` with `reg_step`) for API requests; redirect for web.

### 1.6 CORS / CSRF
- `config/cors.php`: `paths => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout']`, `supports_credentials => true`.
- `.env`: set `SANCTUM_STATEFUL_DOMAINS=localhost,localhost:5173,127.0.0.1,127.0.0.1:5173,dotportal.test`.
- `SESSION_DOMAIN` matches the host.

## Frontend

### 1.7 Create `resources/spa/`
```
resources/spa/
  index.html
  main.ts
  App.vue
  api/
    client.ts
  router/
    index.ts
    routes.ts
  stores/
    auth.ts
  layouts/
    AppLayout.vue            ← copied from Sakai
    AppTopbar.vue
    AppSidebar.vue
    AppMenu.vue
  views/
    PingView.vue             ← smoke screen
    NotFound.vue
  assets/
    layout/                  ← Sakai SCSS
  styles/
    main.scss
```

### 1.8 Pull in Sakai
Sakai Vue (`sakai-vue` on the PrimeFaces GitHub) is MIT-licensed. Copy:
- `layout/`, `assets/layout/`, `assets/styles/` into `resources/spa/`.
- Strip demo pages we don't need; keep the shell + menu component.

### 1.9 Dependencies
`package.json`:
```jsonc
{
  "dependencies": {
    "vue": "^3.4",
    "vue-router": "^4.4",
    "pinia": "^2.2",
    "axios": "^1.7",
    "primevue": "^4.0",
    "primeicons": "^7.0",
    "@primevue/themes": "^4.0"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^5.1",
    "typescript": "^5.5",
    "vue-tsc": "^2.0",
    "sass": "^1.78",
    "vite": "^5.0",
    "laravel-vite-plugin": "^1.0"
  }
}
```
Drop `alpinejs`, `@tailwindcss/forms`, `@popperjs/core` — unused by the SPA.

### 1.10 Vite config
`vite.config.js`:
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'node:path';

export default defineConfig({
  plugins: [
    laravel({ input: ['resources/spa/main.ts'], refresh: true }),
    vue(),
  ],
  resolve: {
    alias: { '@': path.resolve(__dirname, 'resources/spa') },
  },
  server: { host: '127.0.0.1' },
});
```

### 1.11 axios client
`resources/spa/api/client.ts`:
```ts
import axios from 'axios';

export const api = axios.create({
  baseURL: '/api/v1',
  withCredentials: true,
  withXSRFToken: true,
  headers: { Accept: 'application/json' },
});

export async function bootstrapCsrf() {
  await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
}

api.interceptors.response.use(
  r => r,
  err => {
    if (err.response?.status === 401) {
      // auth store will react; router pushes /login
      window.dispatchEvent(new CustomEvent('auth:expired'));
    }
    return Promise.reject(err);
  }
);
```

### 1.12 Main entry
`resources/spa/main.ts`:
```ts
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import App from './App.vue';
import router from './router';
import './styles/main.scss';

createApp(App)
  .use(createPinia())
  .use(router)
  .use(PrimeVue, { theme: { preset: Aura, options: { darkModeSelector: '.app-dark' } } })
  .mount('#app');
```

### 1.13 Single Blade host page
The SPA is served from one Blade file that just emits the Vite assets and a `<div id="app">`:
`resources/views/spa.blade.php`:
```blade
<!doctype html>
<html lang="en"><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>DOT Portal</title>
  @vite(['resources/spa/main.ts'])
</head><body><div id="app"></div></body></html>
```
This view is not mounted on `/` yet — that happens at step 17 (cutover). For now host it at `/spa-preview` so we can develop without breaking the live dashboard.
In `routes/web.php`:
```php
Route::get('/spa-preview/{any?}', fn () => view('spa'))->where('any', '.*')->name('spa.preview');
```

## Tests

`tests/Feature/Api/V1/FoundationTest.php`:
```php
public function test_ping_returns_json() {
    $this->getJson('/api/v1/ping')
         ->assertOk()
         ->assertJsonStructure(['ok', 'time']);
}

public function test_csrf_cookie_endpoint_exists() {
    $this->get('/sanctum/csrf-cookie')->assertNoContent();
}

public function test_role_middleware_returns_json_403_for_api() {
    $user = User::factory()->create();      // no admin role
    Sanctum::actingAs($user);
    // attach a temp route in tests OR test against any existing api route once it's gated
    // …
}
```

## Done when
- `npm run dev` boots the SPA at `/spa-preview` showing the Sakai shell and a "ping ok" smoke screen.
- `/api/v1/ping` returns JSON in the browser and in the test.
- `/sanctum/csrf-cookie` sets the `XSRF-TOKEN` cookie.
- `php artisan test --filter=FoundationTest` is green.
- `progress.md` step 01 checked.
