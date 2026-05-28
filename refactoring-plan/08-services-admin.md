# Step 08 — Services / Service Fields / Service Groups (admin)

Admin/manager surface for defining the catalogue users buy through "Service Requests". Three related resources.

## Services (`/api/v1/services`)
| Method | Path                                          |
|--------|-----------------------------------------------|
| GET    | `/`                                           |
| POST   | `/`                                           |
| GET    | `/{service}`                                  |
| PUT    | `/{service}`                                  |
| DELETE | `/{service}`                                  |
| POST   | `/{service}/status`                           | flip active flag |
| POST   | `/{service}/fields`                           | attach a field   |
| PUT    | `/{service}/fields/{field}`                   | update mapping   |
| DELETE | `/{service}/fields/{field}`                   | detach           |

Middleware: `auth:sanctum`, `hasRole:admin,manager`.

## Service Fields (`/api/v1/service-fields`)
Standalone library of field definitions. Full CRUD (`/`, `/{field}`).

## Service Groups (`/api/v1/service-groups`)
Full CRUD.

## Controllers
- `ServiceController`, `ServiceFieldController`, `ServiceGroupController` — delegate to existing `ServiceAdminActions`, `ServiceFieldActions`, `ServiceGroupActions`.

## FormRequests
- `StoreServiceRequest`, `UpdateServiceRequest` (covers status update if `action` flag present, but keep separate `UpdateServiceStatusRequest` to make the endpoint single-purpose).
- `StoreServiceFieldRequest`, `UpdateServiceFieldRequest`.
- `StoreServiceGroupRequest`, `UpdateServiceGroupRequest`.
- `AttachServiceFieldRequest` — for `/services/{service}/fields`.

## Resources
- `ServiceResource` with `fields[]` (loaded conditionally).
- `ServiceFieldResource` — id, key, label, type, options.
- `ServiceGroupResource`.

## SPA pieces
- `stores/services.ts`, `stores/serviceFields.ts`, `stores/serviceGroups.ts`.
- Views: each section gets list + form views; service form has a "Fields" sub-tab for attach/detach.
- Field builder uses PrimeVue `Dropdown` for type and `Editor` for description; predefined-values editor stored as JSON.

## Tests — `tests/Feature/Api/V1/Services/`, `…/ServiceFields/`, `…/ServiceGroups/`
- Standard CRUD matrix per resource.
- Role gate: `companyUser` → 403 on all writes.
- Field attachment: attach, list, update mapping, detach.
- Status flip: assert column changes both directions.

## Done when
- Three CRUD surfaces work fully from the SPA.
- `php artisan test tests/Feature/Api/V1/Services tests/Feature/Api/V1/ServiceFields tests/Feature/Api/V1/ServiceGroups` green.
