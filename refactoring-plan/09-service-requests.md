# Step 09 — Service Requests (user) + Request Manage (admin)

The transactional core of the portal: users submit service requests, admins/managers triage them.

## User-side (`/api/v1/service-requests`)
Middleware: `auth:sanctum`, `user.isActive`, `hasRole:driver,company`.

| Method | Path                                                                    | Notes                          |
|--------|-------------------------------------------------------------------------|--------------------------------|
| GET    | `/`                                                                     | history list (filters: q, status, group, date range) |
| GET    | `/{request}`                                                            | full detail                    |
| GET    | `/{request}/payments`                                                   | linked payments                |
| GET    | `/{request}/pay`                                                        | payment form data (amount, cards, gateway)  |
| POST   | `/{request}/pay`                                                        | process payment                |
| GET    | `/groups/{group}`                                                       | catalogue page per group       |
| GET    | `/groups/{group}/services/{service}`                                    | dynamic field schema + draft values |
| POST   | `/groups/{group}/services/{service}`                                    | submit new request             |

## Admin-side (`/api/v1/admin/requests`)
Middleware: `auth:sanctum`, `hasRole:admin,manager`.

| Method | Path                                          | Notes |
|--------|-----------------------------------------------|-------|
| GET    | `/`                                           | all requests, advanced filters |
| GET    | `/overview`                                   | KPI / summary view             |
| GET    | `/{request}`                                  | full detail                    |
| PUT    | `/{request}`                                  | basic update                   |
| POST   | `/{request}/status`                           | move status                    |
| POST   | `/{request}/fields`                           | mass-update field values       |
| DELETE | `/{request}`                                  | destroy                        |

## Controllers
- User: `ServiceRequestController`, `ServiceRequestPaymentController`, `ServiceRequestCatalogController`, `ServiceRequestSubmissionController`.
- Admin: `AdminRequestController`, `AdminRequestStatusController`, `AdminRequestFieldsController`, `AdminRequestOverviewController`.
- Delegate to existing `RequestUserActions`, `RequestAdminActions`, `AdminRequestController` action class.

## FormRequests
- `SubmitServiceRequestRequest` — `fields` is an object keyed by service field id; validation rules built dynamically from the service's attached fields (use `Validator::make` inside `rules()`, hydrated from DB).
- `UpdateRequestRequest`, `UpdateRequestStatusRequest`, `UpdateRequestFieldsRequest`.
- `PayServiceRequestRequest` — `gateway`, `card_id|nullable`, `amount`, payment-method specifics.

## Resources
- `ServiceRequestListItemResource` (slim).
- `ServiceRequestDetailResource` — with `fields[]`, `history[]`, `payments[]`, `attachments[]`.
- `ServiceCatalogResource` — group + services with completion flags.
- `ServiceFormSchemaResource` — fields[] with types, options, validation hints, predefined values — the SPA renders the form from this.
- `RequestOverviewResource` — KPIs.

## SPA pieces
- `stores/serviceRequests.ts`, `stores/adminRequests.ts`.
- Views:
  - User: `ServiceCatalogView` (groups), `ServiceFormView` (dynamic form), `RequestHistoryView`, `RequestDetailView`, `RequestPayView`.
  - Admin: `AdminRequestListView`, `AdminRequestDetailView`, `AdminRequestOverviewView`.
- Dynamic form renderer: a `<DynamicForm :schema="schema" v-model="values" />` component that maps field type → PrimeVue component (`InputText`, `Calendar`, `Dropdown`, `FileUpload`, `Textarea`, etc.).

## Tests — `tests/Feature/Api/V1/ServiceRequests/` + `…/AdminRequests/`
User side:
- Catalogue: GET `/groups/{group}` returns services; subscription gate enforced.
- Schema: `/groups/{group}/services/{service}` returns expected field set.
- Submit: happy + validation (dynamic fields).
- Pay: with mocked gateway (use a `PaymentGateway` fake) → 200, no real charge.

Admin side:
- Index filters work (status, date range).
- Status transitions allowed and forbidden ones (e.g. closed → open) — 422 on disallowed.
- Field mass update persists; history row created.
- Role gate: company user → 403.

## Done when
- User can submit a request and pay; admin can move it through statuses; all flows hit JSON endpoints only.
- `php artisan test tests/Feature/Api/V1/ServiceRequests tests/Feature/Api/V1/AdminRequests` green.
