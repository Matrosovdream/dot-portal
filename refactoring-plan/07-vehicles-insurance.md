# Step 07 — Vehicles & Insurance Vehicles

Same shape as drivers — index/CRUD with several sub-resources, plus a related `insurance-vehicles` section.

## Vehicles (`/api/v1/vehicles`)
| Method | Path                                                   | Notes |
|--------|--------------------------------------------------------|-------|
| GET    | `/`                                                    | list, filters: `q`, `unit_type_id`, `ownership_type_id` |
| POST   | `/`                                                    | create |
| GET    | `/{vehicle}`                                           | header |
| PUT    | `/{vehicle}`                                           | basic update |
| DELETE | `/{vehicle}`                                           | destroy |
| GET    | `/{vehicle}/profile`, PUT                              | |
| GET    | `/{vehicle}/insurance`, PUT                            | |
| GET    | `/{vehicle}/inspections`                               | list only (no admin CRUD in current code) |
| GET    | `/{vehicle}/crashes`                                   | list only |
| GET    | `/{vehicle}/driver-history`                            | list |
| POST   | `/{vehicle}/driver-history`                            | add |
| PUT    | `/{vehicle}/driver-history/{drh}`                      | update |
| DELETE | `/{vehicle}/driver-history/{drh}`                      | destroy |
| GET    | `/{vehicle}/logs`                                      | activity log |

Middleware: `auth:sanctum`, `user.isActive`. Policy: company users see own vehicles; admins see all.

## Insurance Vehicles (`/api/v1/insurance-vehicles`)
| Method | Path                              | Notes |
|--------|-----------------------------------|-------|
| GET    | `/`                               | list |
| POST   | `/`                               | create |
| GET    | `/{insurance}`                    | header |
| PUT    | `/{insurance}`                    | update |
| DELETE | `/{insurance}`                    | destroy |
| GET    | `/{insurance}/profile`, PUT       | profile tab |

## Controllers
- `VehicleController`, `VehicleProfileController`, `VehicleInsuranceController`, `VehicleInspectionController`, `VehicleCrashController`, `VehicleDriverHistoryController`, `VehicleLogsController`.
- `InsuranceVehicleController`, `InsuranceVehicleProfileController`.
- All delegate to existing `VehicleUserActions` and `InsuranceVehicleActions`.

## FormRequests
- `StoreVehicleRequest`, `UpdateVehicleRequest` (covers profile too).
- `UpdateVehicleInsuranceRequest`.
- `StoreDriverHistoryRequest`, `UpdateDriverHistoryRequest`.
- `StoreInsuranceVehicleRequest`, `UpdateInsuranceVehicleRequest`.

## Resources
- `VehicleListItemResource`, `VehicleDetailResource`.
- `VehicleInsuranceResource`, `VehicleInspectionResource`, `VehicleCrashResource`, `DriverHistoryResource`.
- `InsuranceVehicleListItemResource`, `InsuranceVehicleDetailResource`.

## SPA pieces
- `stores/vehicles.ts`, `stores/insuranceVehicles.ts` — identical patterns to drivers store.
- Views mirror drivers:
  - `views/vehicles/{VehicleListView,VehicleFormView,VehicleProfileTab,VehicleInsuranceTab,VehicleInspectionsTab,VehicleCrashesTab,VehicleDriverHistoryTab,VehicleLogsTab}.vue`.
  - `views/insurance-vehicles/{...}.vue`.

## Tests — `tests/Feature/Api/V1/Vehicles/` and `…/InsuranceVehicles/`
Same per-endpoint matrix as drivers: auth, role, happy, validation, 404, persistence. Inspections/crashes endpoints are read-only — only happy + auth + empty list cases.

## Done when
- Every `dashboard.vehicles.*` and `dashboard.insurance-vehicles.*` route has a JSON equivalent.
- Driver-history nested CRUD works.
- `php artisan test tests/Feature/Api/V1/Vehicles tests/Feature/Api/V1/InsuranceVehicles` green.
