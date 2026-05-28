# Step 13 — Notifications, To-Do, Search, Documents, Saferweb

Cluster of small read-mostly endpoints that the SPA shell consumes (badges, search bar, document list).

## Notifications
User-facing (`/api/v1/notifications`):
| Method | Path                    | Notes |
|--------|-------------------------|-------|
| GET    | `/`                     | paginated list + unread count in meta |
| PUT    | `/{notification}/read`  | mark single read |
| POST   | `/read-all`             | mark all read |

Admin (`/api/v1/admin/notifications-manage`): full CRUD against `NotificationsAdminController` source.

## To-Do (`/api/v1/todo`)
| Method | Path           |
|--------|----------------|
| GET    | `/`            | overall (mix of company/vehicle/driver tasks) |
| GET    | `/company`     |
| GET    | `/vehicle`     |
| GET    | `/driver`      |
| GET    | `/{task}`      |

Each list endpoint accepts `?status=open|done&overdue=1`.

## Search (`/api/v1/search`)
| Method | Path        |
|--------|-------------|
| GET    | `/global`   | `?q=…` → typed buckets: drivers, vehicles, requests, services, companies |

Implementation may use Scout/Meilisearch (already in composer) — keep behind `SearchActions`.

## Documents (`/api/v1/documents`)
| Method | Path  | Notes                                                   |
|--------|-------|---------------------------------------------------------|
| GET    | `/`   | aggregated list across drivers/vehicles/companies       |

## Saferweb (`/api/v1/saferweb`)
| Method | Path                                  |
|--------|---------------------------------------|
| GET    | `/inspections`                        |
| GET    | `/inspections/{inspection}`           |
| GET    | `/crashes`                            |
| GET    | `/crashes/{crash}`                    |

Middleware: `auth:sanctum`, `user.isActive`, `hasRole:driver,company` for the user-facing ones; admin can read all via separate routes if needed.

## Controllers
One controller per resource family; delegate to existing `NotificationUserActions`, `NotificationAdminActions`, `ToDoActions`, `SearchActions`, `DocumentActions`, `SaferwebActions`.

## FormRequests
Light validation only; mostly query parameters via `Request::validate` inside controller for `q`, `status`, `overdue` — acceptable for read endpoints.

## Resources
- `NotificationResource` — id, type, title, body, read_at, link, meta.
- `TaskResource`.
- `SearchHitResource` — generic `{ type, id, label, sublabel, url }`.
- `DocumentResource` — id, type, name, owner, file (url + size).
- `InspectionResource`, `CrashResource`.

## SPA pieces
- `stores/notifications.ts` — polled every 60s while the SPA is open; mutates the topbar badge.
- `stores/todo.ts`, `stores/search.ts`, `stores/documents.ts`, `stores/saferweb.ts`.
- Topbar search uses `<AutoComplete>` against `/search/global` with 200ms debounce.

## Tests — split per resource:
- `tests/Feature/Api/V1/Notifications/`
- `tests/Feature/Api/V1/Todo/`
- `tests/Feature/Api/V1/Search/`
- `tests/Feature/Api/V1/Documents/`
- `tests/Feature/Api/V1/Saferweb/`

Coverage matrix as usual (auth, role, happy, validation where applicable, persistence for the few writes).

## Done when
- Topbar bell, To-Do, and search all driven by JSON endpoints.
- `php artisan test tests/Feature/Api/V1/Notifications tests/Feature/Api/V1/Todo tests/Feature/Api/V1/Search tests/Feature/Api/V1/Documents tests/Feature/Api/V1/Saferweb` green.
