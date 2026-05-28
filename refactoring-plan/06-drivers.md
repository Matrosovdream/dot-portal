# Step 06 — Drivers

Largest user-facing section. Index, terminated list, full CRUD, plus seven sub-resources (`profile`, `license`, `cdl-license`, `address`, `medical-card`, `drugtest`, `mvr`) + `todo`, `logs`, `send-onetime`, `terminate`.

## Endpoints (`/api/v1/drivers`)
| Method | Path                                          | Notes                            |
|--------|-----------------------------------------------|----------------------------------|
| GET    | `/`                                           | `?status=active|terminated&q=`   |
| POST   | `/`                                           | create                           |
| GET    | `/{driver}`                                   | header view-model                |
| PUT    | `/{driver}`                                   | basic update                     |
| DELETE | `/{driver}`                                   | destroy                          |
| POST   | `/{driver}/terminate`                         | flag terminated                  |
| POST   | `/{driver}/send-onetime`                      | mail one-time link               |
| GET    | `/{driver}/profile`                           | tab data                         |
| PUT    | `/{driver}/profile`                           | tab save                         |
| GET    | `/{driver}/license`, PUT                      |                                  |
| GET    | `/{driver}/cdl-license`, PUT                  |                                  |
| GET    | `/{driver}/address`, PUT                      |                                  |
| GET    | `/{driver}/medical-card`, PUT                 |                                  |
| GET    | `/{driver}/drug-test`, PUT                    |                                  |
| GET    | `/{driver}/mvr`, PUT                          |                                  |
| GET    | `/{driver}/todo`                              | task list                        |
| GET    | `/{driver}/logs`                              | activity log                     |

Middleware: `auth:sanctum`, `user.isActive`. No role gate at section level — admin / manager / company all read; driver sees self only. Add an `OwnDriverScope` policy that limits non-admins to `company_id === auth()->id()` (existing scoping logic in `DriverUserActions`).

## Controller layout
Split into one controller per concern to keep methods small:
- `DriverController` — index/store/show/update/destroy + terminate, send-onetime.
- `DriverProfileController` — show/update.
- `DriverLicenseController`, `DriverCdlLicenseController`, `DriverAddressController`, `DriverMedicalCardController`, `DriverDrugTestController`, `DriverMvrController` — each: `show`, `update`.
- `DriverTodoController@index`, `DriverLogsController@index`.

All delegate to `DriverUserActions` (existing).

## FormRequests
One per sub-resource matching the existing validation in `DriverController.php`. Add `authorize()` calls that check the policy.

## Resources
- `DriverResource` — id, name, status, type, dates, derived flags.
- `DriverDetailResource` — header + completeness flags per tab.
- One resource per sub-resource (`DriverLicenseResource`, etc.).
- `DriverListItemResource` for `index` (compact).

## Pagination & filters
`index` accepts `page`, `per_page` (default 25, cap 100), `q` (name/email LIKE), `status` (active/terminated), `type_id`. Returns `data[] + meta + links`.

## SPA pieces
- `stores/drivers.ts` — `list({ filters })`, `show(id)`, `save(id, tab, payload)`, `terminate(id)`, `sendOneTime(id)`.
- Views:
  - `views/drivers/DriverListView.vue` — PrimeVue `DataTable` with lazy load, filters, sort.
  - `views/drivers/DriverFormView.vue` — new/edit container, tabs.
  - One tab component per sub-resource: `DriverProfileTab.vue`, etc.
- Route nesting:
  ```
  /drivers
  /drivers/new
  /drivers/:id/profile
  /drivers/:id/license
  …
  ```
- File uploads route through step 16's `/api/v1/files` then store `file_id`.

## Tests — `tests/Feature/Api/V1/Drivers/`
- `DriverIndexTest`
  - default lists active drivers.
  - `?status=terminated` filters.
  - `?q=` matches by name.
  - pagination meta correct.
- `DriverCrudTest`
  - store happy path → 201 + DB row.
  - store invalid → 422.
  - show 404 for foreign driver (policy).
  - update persists.
  - destroy soft-deletes (or hard, match existing behaviour).
- `DriverSubResourcesTest`
  - one block per sub-resource: GET shape, PUT happy, PUT validation, role-scoped 403.
- `DriverActionsTest`
  - terminate flips flag.
  - send-onetime dispatches mail (assert `Mail::fake`).

## Done when
- Every old `dashboard.drivers.*` route has a JSON equivalent.
- SPA driver list + edit tabs work end-to-end against a seeded DB.
- `php artisan test tests/Feature/Api/V1/Drivers` green.
