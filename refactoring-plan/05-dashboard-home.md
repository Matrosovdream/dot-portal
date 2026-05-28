# Step 05 — Dashboard Home (role-aware)

Port `dashboard.home`. Old logic switches on role and returns one of four Blade views (`home.admin`, `home.user`, `home.driver`). The API equivalent returns one endpoint whose payload **shape** depends on the role; the SPA chooses a widget set.

## Endpoint
| Method | Path                       | Middleware             |
|--------|----------------------------|------------------------|
| GET    | `/api/v1/dashboard/home`   | auth:sanctum, user.isActive |

## Response envelope
```json
{
  "role": "admin|manager|company|driver",
  "widgets": {
    "kpis": [{ "key": "revenue", "label": "Revenue", "value": 12000, "delta": 0.04 }],
    "recent_requests": [...],
    "drivers_expiring": [...],
    "vehicles_expiring": [...],
    "todo_summary": { "open": 12, "overdue": 3 },
    "subscription": { "active": true, "renews_at": "..." }
  }
}
```
Which keys appear under `widgets` is role-dependent. The SPA renders a `<DashboardHome :role>` component that picks tiles by `role` + presence of each key.

## Controller
`App\Http\Controllers\Api\V1\Dashboard\HomeController@show` — same switch on role, but each branch calls its existing `Home*Actions::index()` and wraps the return in a `DashboardHomeResource`.

## Resource
`DashboardHomeResource` — known shape per role; missing widgets are simply absent (`when()` conditional includes).

## SPA pieces
- `views/HomeView.vue` (Sakai's Dashboard.vue, gutted).
- Composables per widget: `useKpis`, `useRecentRequests`, etc., each binds to `store.home.widgets.<key>`.
- Mobile-friendly grid using PrimeVue `Card` + Sakai layout grid.

## Tests
- One test per role: seed user, hit endpoint, assert `role` field + presence of expected keys.
- `test_unauthenticated_401`.
- `test_inactive_user_blocked` — user with `is_active=false` → 403/redirect-equivalent JSON.

## Done when
- One endpoint serves four roles, the SPA chooses widgets from response.
- `php artisan test tests/Feature/Api/V1/Dashboard` green.
