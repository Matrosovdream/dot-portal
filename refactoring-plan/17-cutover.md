# Step 17 — Cutover: retire Blade dashboard

All sections (steps 02–16) are JSON + SPA. Now flip `/` to the SPA and delete the old Blade dashboard.

## Backend changes

### 17.1 Strip the old dashboard route file
- Delete `routes/dashboard.php` entirely.
- Delete `routes/user.php` (the "/" and "/lg/" routes — replaced by the SPA shell + a small server-rendered marketing page if needed; see 17.4).
- `routes/web.php` reduced to:
  ```php
  use Illuminate\Support\Facades\Route;

  Route::get('/{any?}', fn () => view('spa'))
      ->where('any', '^(?!api|sanctum|up|storage|file).*')
      ->name('spa');
  ```
  Everything except API, Sanctum, health, storage, and any small remaining web endpoints falls through to the SPA shell, which then dispatches via `vue-router`.

### 17.2 Delete dashboard controllers and Blade views
- Remove `app/Http/Controllers/Dashboard/*` (Actions remain — still used by API controllers).
- Remove `resources/views/dashboard/` and `resources/views/auth/` (Breeze views).
- Remove `resources/js/admin/` and `resources/js/user/`.
- Remove `tailwind.config.js`, `postcss.config.js`, `@tailwindcss/forms`, `alpinejs`, `@popperjs/core` if the SPA doesn't use Tailwind.

### 17.3 Auth controllers
The Breeze HTML controllers (`AuthenticatedSessionController@create`, etc.) are dead. Keep only the API auth controllers added in step 02. Drop `RegisteredUserController@create`, `…@registerRemove` (HTML methods).

### 17.4 Public/marketing pages
`routes/user.php` had a `/` and `/lg/` index. If those are public landing pages they belong in the SPA too — add `LandingView.vue` (no auth required). If they're a separate server-rendered site, keep them as plain Blade routes and exclude them from the SPA catch-all.

Decide per the existing `IndexController@index` content — verify before deleting.

### 17.5 Single Blade host page
The `resources/views/spa.blade.php` from step 01 becomes the only Blade view. Confirm it renders the production Vite bundle correctly:
```
npm run build && php artisan optimize:clear
```

## Frontend changes

### 17.6 Move SPA mount point
Step 01 mounted the SPA at `/spa-preview` so the live dashboard kept working. Now that `/` is the SPA catch-all, remove the `spa-preview` route and any references.

### 17.7 Production hardening
- Source maps disabled (`vite.config.js: build.sourcemap: false`).
- CSP headers via middleware (`app/Http/Middleware/SetSecurityHeaders.php`).
- Asset hashing handled by Vite; cache headers via Nginx/Apache for `/build/*`.

## Tests

### 17.8 Smoke suite
Add `tests/Feature/Api/V1/CutoverTest.php`:
- Old dashboard URLs return 200 with the SPA shell (HTML containing `<div id="app">`), not 404 — proves the catch-all works.
- `GET /api/v1/auth/me` for guest → 401.
- `GET /up` → 200.
- `GET /storage/*` still serves files.
- Auth flow E2E (use Laravel Dusk if available, otherwise a manual smoke checklist) for each role:
  - login → home → at least one section per role works → logout → back to /login.

### 17.9 Full test run
```
php artisan test
```
must be green. Any failing test from the legacy suite that referenced removed Blade routes/views must be either ported to the API equivalent or deleted as obsolete.

## Risk checklist (review before merging cutover)
- [ ] Every old route in `section-map.md` has a passing API test.
- [ ] Every role can log in and see its home + key sections.
- [ ] Background jobs (Horizon, mail, payment webhooks) still target the right URLs.
- [ ] Payment webhook URLs are NOT prefixed with `/api/v1` accidentally — keep their existing public paths.
- [ ] Email templates that link to the dashboard now point to SPA URLs.
- [ ] Tests around `OneTimeLogin` still work — the link in email opens an SPA route, not a Blade page.
- [ ] CSRF cookie path covers the SPA host.
- [ ] No `view('dashboard.*')` calls remain in `app/`.

## Done when
- Visiting `/` shows the SPA login → after auth, role-appropriate home.
- `php artisan test` green across the whole suite.
- Final commit deletes the legacy Blade dashboard + assets.
- `progress.md` step 17 checked, smoke list complete, deviation log finalised.

## After cutover (optional follow-ups, NOT part of this plan)
- Drop unused npm deps and run `npm audit fix`.
- Add Cypress / Playwright E2E for the top 3 user journeys.
- Add API rate limits per section beyond the default `60,1`.
- Re-introduce REST resources under `/rest/v1/*` only if external consumers actually need them.
