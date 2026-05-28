# Step 14 — Admin: Users, Settings, Gateways, Notifications Manager

Pure admin surfaces. Middleware: `auth:sanctum`, `hasRole:admin` (users + settings + gateways + notif-manage are admin-only in current code).

## Users (`/api/v1/admin/users`)
| Method | Path                |
|--------|---------------------|
| GET    | `/`                 |
| POST   | `/`                 |
| GET    | `/{user}`           |
| PUT    | `/{user}`           |
| DELETE | `/{user}`           |
| POST   | `/{user}/roles`     | replace user's role set |
| POST   | `/{user}/activate`  | flip `is_active`        |

## Settings (`/api/v1/admin/settings`)
| Method | Path  |
|--------|-------|
| GET    | `/`   |
| PUT    | `/`   |

Single resource — load + save the site settings dictionary.

## Gateways (`/api/v1/admin/gateways`)
| Method | Path                  | Notes |
|--------|-----------------------|-------|
| GET    | `/`                   | list + status |
| GET    | `/{gateway}`          | settings dictionary |
| PUT    | `/{gateway}`          | update credentials |
| POST   | `/{gateway}/test`     | test connection |

## Notifications Manager (`/api/v1/admin/notifications-manage`)
Full CRUD against `Service` notification triggers — keep parity with `NotificationsAdminController`.

## Controllers
- `Admin\UserController`, `Admin\UserRoleController`, `Admin\UserActivationController`.
- `Admin\SettingsController`.
- `Admin\GatewayController`, `Admin\GatewayTestController`.
- `Admin\NotificationsManageController`.
Delegate to existing actions / repositories (`DashboardUsersController` logic + Repositories).

## FormRequests
- `StoreUserRequest`, `UpdateUserRequest` (`email|unique` excluding self).
- `UpdateRolesRequest` — `roles: array of role slugs`.
- `UpdateSettingsRequest` — schema-driven (settings keys vary; validate as `array`).
- `UpdateGatewayRequest` — gateway-specific (use a switch on gateway slug).
- `StoreNotificationRuleRequest`, `UpdateNotificationRuleRequest`.

## Resources
- `AdminUserResource` — user + roles + active + last login.
- `RoleResource`.
- `SettingResource` (single).
- `GatewayResource` — slug, label, status, fields[] for the form.
- `NotificationRuleResource`.

## SPA pieces
- `stores/admin/users.ts`, `stores/admin/settings.ts`, `stores/admin/gateways.ts`, `stores/admin/notificationRules.ts`.
- Views under `views/admin/users/`, `…/settings/`, `…/gateways/`, `…/notifications/`.
- Gateway forms render from `fields[]` so adding a new gateway requires only backend changes.

## Tests — `tests/Feature/Api/V1/Admin/`
- `UsersTest` — standard CRUD + role replacement + activation toggle. Self-edit not allowed for destroy.
- `SettingsTest` — load, update, validation, role gate (manager → 403).
- `GatewaysTest` — list, update, test endpoint (with a fake adapter).
- `NotificationsManageTest` — full CRUD.

## Done when
- All admin management screens drive entirely from JSON.
- `php artisan test tests/Feature/Api/V1/Admin` green.
