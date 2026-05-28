# Step 04 — References & Globals

Centralised read-only endpoints the SPA needs to populate dropdowns, labels, currency, role flags, feature toggles. Most of these existed implicitly inside Blade controllers — surface them as their own section so every screen can consume them once at boot.

## Endpoints (`/api/v1/references` + `/api/v1/globals`)
| Method | Path                                              | Source                              |
|--------|---------------------------------------------------|-------------------------------------|
| GET    | `/api/v1/references/states`                       | `RefCountryStates`                  |
| GET    | `/api/v1/references/driver-types`                 | `RefDriverType`                     |
| GET    | `/api/v1/references/license-types`                | `RefDriverLicenseType`              |
| GET    | `/api/v1/references/license-endorsements`         | `RefDriverLicenseEndrs`             |
| GET    | `/api/v1/references/vehicle-ownership-types`      | `RefVehicleOwnershipType`           |
| GET    | `/api/v1/references/vehicle-unit-types`           | `RefVehicleUnitType`                |
| GET    | `/api/v1/references/form-fields`                  | `ReferenceFormField` / `RefFormFields` |
| GET    | `/api/v1/references/query-prices`                 | `RefQueryPrice`                     |
| GET    | `/api/v1/references/payment-methods`              | `RefPaymentMethod`                  |
| GET    | `/api/v1/references/request-statuses`             | `RefRequestStatus`                  |
| GET    | `/api/v1/references/order-statuses`               | `RefOrderStatus`                    |
| GET    | `/api/v1/references/service-groups`               | `RefServiceGroup`                   |
| GET    | `/api/v1/references/bundle`                       | all of the above in one call        |
| GET    | `/api/v1/globals`                                 | `SiteSettingsService` + `GlobalsService` |

Middleware: `auth:sanctum` only. No role gate (these are shared lookups).

## Controllers
- `App\Http\Controllers\Api\V1\References\ReferencesController` — one method per resource, each returning a Resource collection.
- `App\Http\Controllers\Api\V1\GlobalsController@show`.

## Caching
Wrap each reference fetch in `Cache::remember("ref:{$key}", 3600, fn () => …)`. Reference data changes rarely; without caching every page load hits 10+ tables.

Bust via a `ReferencesObserver` on each Ref* model that calls `Cache::forget("ref:{$key}")` on save/delete.

## Resources
Generic shape:
```json
{ "id": 1, "code": "CA", "name": "California", "meta": {} }
```
Field selection per ref type — `meta` carries anything UI-specific.

## SPA pieces
- `stores/references.ts` — one async `loadAll()` that fires `/references/bundle` once, caches in memory; per-section getters: `states`, `driverTypes`, etc.
- `stores/globals.ts` — `load()` fires `/globals`, returns `{ currency, siteName, featureFlags, locale }`.
- Both stores are awaited inside `App.vue`'s `onMounted`, parallel with `auth.bootstrap()`.

## Tests — `tests/Feature/Api/V1/References/`
- `BundleTest::test_bundle_returns_all_keys` — assertJsonStructure with every ref key.
- One test per endpoint asserting `data` array shape.
- `test_cache_hit` — call twice, assert query count drops the second time (use `DB::enableQueryLog`).
- `test_unauthenticated_returns_401`.

## Done when
- `/api/v1/references/bundle` returns every dictionary the SPA needs.
- Reference cache busts when a Ref* model is saved.
- `php artisan test tests/Feature/Api/V1/References` green.
