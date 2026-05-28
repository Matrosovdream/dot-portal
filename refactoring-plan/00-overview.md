# dot-portal — Backend API + Vue SPA Refactor Plan

## Goal
Rewrite the current Laravel + Blade dashboard as:
1. A clean **JSON API** under `/api/v1/{section}` (SPA-shaped responses, not strict REST).
2. A fully **client-rendered Vue 3 SPA** built on the **Sakai PrimeVue** theme.
3. **Feature tests** for every API endpoint, written immediately after each step lands.

This is a **big-bang cutover**: the existing Blade dashboard at `/dashboard/*` will be retired in one move once the SPA reaches parity. No dual-running.

## Decisions (locked)
| Area              | Choice                                           |
|-------------------|--------------------------------------------------|
| Auth              | Laravel Sanctum — SPA cookie session             |
| API style         | SPA-shaped, sectioned under `/api/v1/{section}`  |
| Cutover           | Big-bang rewrite                                 |
| Frontend stack    | Vue 3 + Sakai + PrimeVue + Pinia + Axios         |
| Build tool        | Vite (existing)                                  |
| Tests             | PHPUnit Feature tests (existing harness)         |
| DB / Models       | Untouched — same schema, same Eloquent models    |
| Actions / Repos   | Reused — controllers thin out, Actions stay      |

## Architectural rules

### Backend
- All API routes live in **`routes/api.php`** under prefix `/api/v1/{section}`.
- Stateful via `auth:sanctum` + `EnsureFrontendRequestsAreStateful` middleware.
- One controller per section under `App\Http\Controllers\Api\V1\{Section}\…`.
- Controllers delegate to existing Actions in `App\Actions\Dashboard\*` — no business logic in controllers.
- All responses go through **API Resources** (`App\Http\Resources\V1\…`). No `toArray()` model dumps.
- Validation via **FormRequest** classes (`App\Http\Requests\Api\V1\…`). No inline `$request->validate()`.
- Role gates reuse existing middlewares: `hasRole:admin`, `hasRole:admin,manager`, `hasRole:driver,company`, `user.isActive`. Applied at route group level.
- Error shape is uniform:
  ```json
  { "message": "…", "errors": { "field": ["…"] } }   // 422
  { "message": "Unauthenticated." }                  // 401
  { "message": "Forbidden." }                        // 403
  { "message": "Not found." }                        // 404
  ```
- Pagination via Laravel's `->paginate()` → `JsonResource::collection()` → `{ data, meta, links }`.

### Frontend
- New folder: **`resources/spa/`** — entirely fresh, replaces `resources/js/admin` + `resources/js/user`.
- Single Vite entrypoint: `resources/spa/main.ts`.
- Sakai layout shell adapted to our sections (sidebar groups: Dashboard, Drivers, Vehicles, Insurance, Services, Clearing House, Subscriptions, Admin).
- **Router:** `vue-router` with lazy-loaded route modules, one module per section, mirroring API sections.
- **State:** Pinia stores — one store per section. Stores hold both server data and UI state (no separate cache layer per the chosen stack).
- **HTTP:** single `src/api/client.ts` axios instance with CSRF bootstrap + 401/419 interceptors.
- **Forms:** PrimeVue components + thin composables per form (`useDriverForm`, etc.). Server-side errors map to per-field messages.
- **No SSR**, no Inertia, no Livewire. Strictly client-rendered.

### Tests
- Every API endpoint gets a Feature test before the step is marked done.
- Reuse `Tests\Feature\Traits\EntityTestable` where it fits. Extend it where the SPA shape differs (assert JSON structure, not redirects).
- Authentication tests use `Sanctum::actingAs($user, ['*'])` for stateful auth or a real `POST /login` for the auth flow itself.
- Run with: `php artisan test --testsuite=Feature --filter=<step>` after each step.
- No step is "done" until its tests pass green.

## Step files (read in order)
- [01 — Foundation: Sanctum, /api/v1 skeleton, SPA shell](01-foundation.md)
- [02 — Auth & session API](02-auth-session.md)
- [03 — User profile & company](03-profile-company.md)
- [04 — References & globals](04-references-globals.md)
- [05 — Dashboard home (role-aware)](05-dashboard-home.md)
- [06 — Drivers section](06-drivers.md)
- [07 — Vehicles & Insurance Vehicles](07-vehicles-insurance.md)
- [08 — Service Groups, Services, Service Fields (admin)](08-services-admin.md)
- [09 — Service Requests (user) & Request Manage (admin)](09-service-requests.md)
- [10 — Subscriptions, Plans, Plan Fees, Sub Requests, Sub Manager](10-subscriptions.md)
- [11 — Clearing House (queries, balance, register company)](11-clearing-house.md)
- [12 — Orders & Payments (cards, history, pay flow)](12-orders-payments.md)
- [13 — Notifications, To-Do, Search, Documents, Saferweb](13-notifications-todo-search.md)
- [14 — Admin: Users, Settings, Gateways, Notifications Manager](14-admin-management.md)
- [15 — SPA layout: Sakai shell, routing, role-based menu, error pages](15-spa-layout.md)
- [16 — File upload/download bridge](16-files.md)
- [17 — Cutover: retire Blade dashboard, route `/` → SPA shell, smoke tests](17-cutover.md)

## Progress tracking
See [progress.md](progress.md). Update **after every step** — checkbox the step, paste the failing-then-green test run, and note any deviation from the plan.

## Testing conventions
See [testing-conventions.md](testing-conventions.md).

## Section-to-route mapping
See [section-map.md](section-map.md) — the authoritative crosswalk from old Blade routes to new `/api/v1` endpoints and SPA routes.
