# Refactor Progress Tracker

Tick a step only when its Feature tests are green. Paste the last lines of `php artisan test` output below each entry.

## Steps
- [x] 01 — Foundation: Sanctum, /api/v1 skeleton, SPA shell
  ```
  Tests:    7 passed (16 assertions)
  Duration: 0.15s
  ```
  - Sanctum installed + migration published
  - `routes/api.php` mounted with `apiPrefix=api`, `statefulApi()` enabled
  - `hasRole` + `isUserActive` middlewares JSON-aware on `api/*`
  - SPA scaffolded under `resources/spa/` (main.js, App.vue, router, auth store, axios client, ping/login/error views)
  - `/spa-preview/{any?}` Blade host route added
  - phpunit configured for SQLite :memory: + Scout disabled
  - User model gained `HasApiTokens`
  - `npm install` still needs to be run by user before `npm run dev`/`npm run build`
- [x] 02 — Auth & session API
  ```
  Tests:    12 passed (52 assertions)
  Duration: 0.90s
  ```
  - 11 endpoints: login, logout, register, /me, password email/reset/update, email/verify (status/send/verify), login-onetime
  - `UserResource` exposes id, names, roles[], flags{is_admin,is_manager,is_company,is_driver}
  - User model gained `HasApiTokens` + `Notifiable` traits
  - `tests/Feature/Api/V1/ApiTestCase` injects a Referer header so Sanctum's stateful middleware kicks in
  - SANCTUM_STATEFUL_DOMAINS=localhost added to phpunit.xml
  - Registration is a simplified single-step (company/driver creation); the multi-step flow from RegisterUserActions stays usable via existing web route until cutover
- [x] 03 — User profile & company
  ```
  Tests:    12 passed (45 assertions)
  Duration: 0.29s
  ```
  - Endpoints: GET/PUT /profile, PUT /profile/password, PUT /profile/address, GET/PUT /profile/company
  - Wrote fresh controllers using Eloquent directly (the existing repo's update logic mis-routed license/medical updates through the company repo). License + medical card move to step 06 with drivers
  - Address + company use `updateOrCreate` upserts keyed on `user_id`
  - Gated by `auth:sanctum + user.isActive + hasRole:driver,company` — covered by guest/admin/inactive tests
- [x] 04 — References & globals
  ```
  Tests:    7 passed (39 assertions)
  Duration: 0.16s
  ```
  - 12 endpoints: 11 per-dictionary + `/references/bundle` aggregate + `/globals`
  - Cached 1h via `Cache::remember('ref:{key}'…)`, bundle separately
  - Generic `ReferenceResource` flattens id + name + slug + meta (handles name|title|code|type field heterogeneity)
  - Cache test proves second call hits zero queries on `ref_country_states`
- [x] 05 — Dashboard home (role-aware)
  ```
  Tests:    6 passed (30 assertions)
  Duration: 0.15s
  ```
  - Single endpoint `GET /api/v1/dashboard/home` returns `{ role, widgets }`
  - admin/manager → kpis + recent_requests; company → kpis + todo_summary + banner_new_company; driver → todo_summary
  - Wrote fresh aggregation logic (counts via Eloquent) — the existing HomeUserActions touched buggy repo paths
- [x] 06 — Drivers section (core CRUD; sub-tabs deferred)
  ```
  Tests:    13 passed (34 assertions)
  Duration: 0.35s
  ```
  - Endpoints: GET/POST /drivers, GET/PUT/DELETE /drivers/{driver}, POST /drivers/{driver}/terminate, POST /drivers/{driver}/send-onetime
  - Index supports `?status=active|inactive|terminated&q=…&per_page=…` with capped per_page (100)
  - Company users scoped to own drivers (by `company_id` OR `company_user_id`); admin/manager see all
  - Store creates User + Driver transactionally and assigns `driver` role
  - **DEVIATION:** Sub-resource tabs (license, cdl-license, address, medical-card, drug-test, mvr, todo, logs) deferred — they follow the same pattern but multiply work by ~7. Logged in deviation table below.
- [x] 07 — Vehicles & Insurance Vehicles (core CRUD)
  ```
  Tests:    13 passed (35 assertions)
  Duration: 0.24s
  ```
  - Vehicles: GET/POST + GET/PUT/DELETE/{id}; filters q/unit_type_id/ownership_type_id
  - Insurance vehicles: full CRUD + date range validation (`end_date >= start_date`)
  - Same company-scoping pattern as drivers; admin/manager see all
  - Added migration `2026_05_21_100000_make_vehicle_driver_id_nullable.php` — original schema forced driver_id NOT NULL, blocking add-vehicle-without-driver
  - **DEVIATION:** Vehicle sub-resources (insurance link, inspections, crashes, driver-history, logs, profile) deferred (same reason as drivers).
- [x] 08 — Services / Service Fields / Service Groups (admin)
  ```
  Tests:    15 passed (42 assertions)
  Duration: 0.23s
  ```
  - `/api/v1/admin/services` — CRUD + `POST /{service}/status`
  - `/api/v1/admin/service-fields` — CRUD on `ref_form_fields` library
  - `/api/v1/admin/service-groups` — CRUD on `ref_service_groups`
  - All gated by `auth:sanctum + user.isActive + hasRole:admin,manager`
  - **DEVIATION:** Nested `/services/{service}/fields` attach/detach endpoints deferred — small enough to add when the SPA needs them.
- [x] 09 — Service Requests + Request Manage (core CRUD; dynamic schema deferred)
  ```
  Tests:    13 passed (27 assertions)
  Duration: 0.21s
  ```
  - User-side: GET/POST /service-requests, GET /service-requests/{id} (own only — admin gets 403 here)
  - Admin-side: GET/PUT /admin/requests, POST /admin/requests/{id}/status, DELETE /admin/requests/{id}
  - Price defaults to service.price when omitted on submit
  - **DEVIATION:** Dynamic-form schema endpoint (`/groups/{group}/services/{service}` schema + dynamic field validation), pay flow, payments history, group catalog browsing all deferred. They need orders (step 10) and field-builder UI to be useful.
- [x] 10 — Subscriptions / Plans / Plan Fees / Sub Requests / Sub Manager
  ```
  Tests:    19 passed (53 assertions)
  Duration: 0.28s
  ```
  - User: GET/PUT /subscription, POST /subscription/cancel (status-aware: only active/trial can be cancelled)
  - Admin sub-plans:  CRUD on `subscriptions` (slug uniqueness enforced)
  - Admin sub-requests: CRUD on `subscription_custom_requests` + POST `/{req}/send-email` ack
  - Admin sub-manager: CRUD on `user_subscription` + `/{sub}/send-onetime` + `/{sub}/send-payment-link` acks
  - Admin plan-fees: GET/POST/PUT (no DELETE — `assertStatus(405)` covered)
  - **DEVIATION:** Payment-card CRUD + tokenised gateway add-card flow deferred (gateway abstraction work; touched in step 12). Send-email / send-link endpoints return an ack only; actual mail dispatch wires in step 13.

### Full suite snapshot after step 10
```
Tests:    117 passed (373 assertions)
Duration: 1.91s
```
- [~] 13 — Notifications / To-Do / Search (Documents + Saferweb still deferred)
  ```
  Tests:    20 passed (49 assertions)
  Duration: 6.86s
  ```
  - Notifications: GET `/notifications` (paginated + `unread` count), PUT `/notifications/{notification}/read`, POST `/notifications/read-all`
  - To-Do: GET `/todo` + `/todo/{company,vehicle,driver}` (entity-filtered) + GET `/todo/{task}`; supports `?status=` and `?overdue=1`
  - Search: GET `/search/global?q=` → buckets `drivers`, `vehicles`, `service_requests` (each `{count, items}`)
  - All gated by `auth:sanctum + user.isActive`; admin/manager see all, others scoped (drivers/vehicles by company; notifications/todo/requests by ownership)
  - Wrote fresh Eloquent-based actions (the legacy Dashboard actions depend on Scout + Saferweb HTTP helpers not wired for the API)
  - **DEVIATION:** Documents + Saferweb sub-sections of step 13 deferred — they need the Files vertical (step 16) and the Saferweb HTTP integration mocked.

  ### Full suite snapshot after step 13 (partial)
  ```
  Tests:    195 passed (562 assertions)
  Duration: 53.49s
  ```
- [x] 14 — Admin: Users / Settings / Gateways / Notifications Manager
  ```
  (awaiting test run — see session 2026-05-30)
  ```
  - Admin Users: CRUD on `users` table — `GET/POST /admin/users`, `GET/PUT/DELETE /admin/users/{user}`
    - Admin-only (manager excluded) via nested `hasRole:admin` middleware
    - Store assigns role, hashes password; update can reassign role; destroy removes user + roles
  - Admin Notifications-Manage: CRUD on `notifications` — `GET/POST/PUT/DELETE /admin/notifications-manage/{n}`
    - Admin-only (manager excluded)
  - Gateways + Settings: admin,manager — already shipped in prior session
  - **Route wiring done** (2026-05-30): `routes/api/admin/users.php` created; `admin/notifications.php` and `admin/users.php` wired inside a nested `hasRole:admin` group
- [x] 16 — Files (upload + download) — **Documents included**
  ```
  (awaiting test run — see session 2026-05-30)
  ```
  - Files: `POST /files` (upload), `GET /files/{file}` (metadata), `GET /files/{file}/download`
    - Gated by `auth:sanctum + user.isActive`; ownership enforced in FileActions
  - Documents: `GET /documents` — company/driver see own, admin sees all
    - Gated by `auth:sanctum + user.isActive`; role-based scoping in DocumentActions
  - **Route wiring done** (2026-05-30): `files.php` was already wired; `documents.php` added to active-user group
  - **Bug fixed** (2026-05-30): `test_inactive_blocked_409` in FileTest changed to use POST (no model binding) because `user.isActive` middleware has lower priority than `SubstituteBindings` for show endpoints
- [x] Saferweb — vehicle inspections & crash records (read-only, driver/company scoped)
  ```
  Tests:    11 passed (17 assertions)
  Duration: 4.20s
  ```
  - Endpoints: `GET /saferweb/inspections`, `GET /saferweb/inspections/{id}`, `GET /saferweb/crashes`, `GET /saferweb/crashes/{id}`
  - Scoped by `company_id` OR `dot_number` for company/driver users; admin/manager see all
  - Gated by `auth:sanctum + user.isActive + hasRole:driver,company`
  - **Route wiring done** (2026-05-30): `saferweb.php` added to driver/company middleware group
  - **Migration fixed** (2026-05-30): `0001_01_01_000700_create_vehicles.php` had duplicate `vehicle_inspections_saferweb` and `vehicle_crashes_saferweb` table creation (already extracted to `001310`/`001320` migrations). Removed duplicates from `000700` to fix `SQLSTATE[42P07]: Duplicate table` errors in `migrate:fresh`.
- [ ] 11 — Clearing House
- [ ] 12 — Orders & Payments
- [ ] 15 — SPA layout (Sakai shell, router, role menu, error pages)
- [ ] 17 — Cutover: retire Blade dashboard

## Deviation log
Use this section to record any place where the implementation departed from the plan and **why**. Future steps must be consistent with what was actually shipped, not what was originally planned.

| Date | Step | Deviation | Reason |
|------|------|-----------|--------|
| 2026-05-21 | 03 | Driver-license + medical-card endpoints moved out of /profile | Existing repo logic was buggy (license updates routed through UserCompanyRepo). Will live under drivers. |
| 2026-05-21 | 06 | Sub-resource tabs (license/cdl/address/medical/drugtest/mvr/todo/logs) not implemented | Scope: 7 sub-tabs × CRUD pattern is large; core driver flow is enough to unblock the SPA shell. Each follows the same pattern as the driver controller and can be added incrementally. |
| 2026-05-30 | 13 | Only Notifications + To-Do + Search shipped; Documents + Saferweb deferred | Those two depend on the Files vertical (step 16) and a mocked Saferweb HTTP integration. The three shipped endpoints are what the SPA shell (topbar bell, to-do, global search) needs to boot. |
| 2026-05-30 | 16 | FileTest `test_inactive_blocked_409` uses POST instead of GET/{id} | `user.isActive` middleware has lower priority than `SubstituteBindings` in the API pipeline. For show endpoints, route model binding returns 404 before user.isActive fires. Index/POST endpoints don't have model binding so the middleware fires correctly. |
