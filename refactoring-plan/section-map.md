# Section Map — Old Blade Route → New API & SPA Route

For each section: the existing `dashboard.*` named routes, the new `/api/v1/{section}` endpoints, and the SPA path. Method and middleware are noted in the API column.

Legend: `A` = auth:sanctum, `Act` = user.isActive, `R[…]` = hasRole.

## Auth
| Old                       | API method + path                       | Middleware | SPA path           |
|---------------------------|------------------------------------------|------------|--------------------|
| `login` GET/POST          | `POST /api/v1/auth/login`                | guest      | `/login`           |
| `logout` POST             | `POST /api/v1/auth/logout`               | A          | (action)           |
| `register` GET/POST       | `POST /api/v1/auth/register`             | guest      | `/register`        |
| `register.remove`         | `POST /api/v1/auth/register/remove`      | guest      | `/register`        |
| `password.email`          | `POST /api/v1/auth/password/email`       | guest      | `/forgot-password` |
| `password.store`          | `POST /api/v1/auth/password/reset`       | guest      | `/reset-password`  |
| `password.update`         | `PUT  /api/v1/auth/password`             | A          | (profile)          |
| `password.confirm`        | `POST /api/v1/auth/password/confirm`     | A          | (modal)            |
| `verification.notice`     | `GET  /api/v1/auth/email/verify`         | A          | `/verify-email`    |
| `verification.send`       | `POST /api/v1/auth/email/verify/resend`  | A          | (action)           |
| `verification.verify`     | `GET  /api/v1/auth/email/verify/{id}/{hash}` (signed) | A | (redirect target) |
| `retrieve-usdot`          | `POST /api/v1/auth/retrieve-usdot`       | A          | (register step)    |
| `login.onetime`           | `GET  /api/v1/auth/login-onetime/{token}`| guest      | `/login-onetime/:token` |
| (new) current user        | `GET  /api/v1/auth/me`                   | A          | (bootstrap)        |
| (new) csrf cookie         | `GET  /sanctum/csrf-cookie`              | guest      | (bootstrap)        |

## Dashboard (home)
| Old                       | API                                       | Middleware       | SPA          |
|---------------------------|--------------------------------------------|------------------|--------------|
| `dashboard.home`          | `GET /api/v1/dashboard/home`               | A+Act            | `/`          |

Returns role-shaped payload (admin/manager/user/driver) — the SPA picks the right widget set.

## Profile (user-facing)
| Old                                    | API                                                | MW          | SPA                 |
|----------------------------------------|----------------------------------------------------|-------------|---------------------|
| `dashboard.profile.show`               | `GET  /api/v1/profile`                             | A+Act+R[driver,company] | `/profile` |
| `dashboard.profile.update`             | `PUT  /api/v1/profile`                             | same        | `/profile`          |
| `dashboard.profile.password.update`    | `PUT  /api/v1/profile/password`                    | same        | `/profile`          |
| `dashboard.profile.edit` (form data)   | `GET  /api/v1/profile/edit`                        | same        | `/profile/edit`     |
| `dashboard.profile.address.update`     | `PUT  /api/v1/profile/address`                     | same        | `/profile/edit`     |
| `dashboard.profile.company.edit`       | `GET  /api/v1/profile/company`                     | same        | `/profile/company`  |
| `dashboard.profile.company.update`     | `PUT  /api/v1/profile/company`                     | same        | `/profile/company`  |
| `dashboard.profile.driverlicense.*`    | `GET/PUT /api/v1/profile/driver-license`           | same        | `/profile/license`  |
| `dashboard.profile.medicalcard.*`      | `GET/PUT /api/v1/profile/medical-card`             | same        | `/profile/medical`  |

## Drivers (`/api/v1/drivers`)
| Old                                              | API                                              | MW   |
|--------------------------------------------------|--------------------------------------------------|------|
| `dashboard.drivers.index`                        | `GET    /api/v1/drivers`                         | A+Act|
| `dashboard.drivers.terminated`                   | `GET    /api/v1/drivers?status=terminated`       | A+Act|
| `dashboard.drivers.store`                        | `POST   /api/v1/drivers`                         | A+Act|
| `dashboard.drivers.show`                         | `GET    /api/v1/drivers/{driver}`                | A+Act|
| `dashboard.drivers.update`                       | `PUT    /api/v1/drivers/{driver}`                | A+Act|
| `dashboard.drivers.destroy`                      | `DELETE /api/v1/drivers/{driver}`                | A+Act|
| `dashboard.drivers.terminate`                    | `POST   /api/v1/drivers/{driver}/terminate`      | A+Act|
| `dashboard.drivers.send.oncelogin`               | `POST   /api/v1/drivers/{driver}/send-onetime`   | A+Act|
| `dashboard.drivers.show.profile` (GET/PUT)       | `GET/PUT /api/v1/drivers/{driver}/profile`       | A+Act|
| `dashboard.drivers.show.license` (GET/PUT)       | `GET/PUT /api/v1/drivers/{driver}/license`       | A+Act|
| `dashboard.drivers.show.cdl-license`             | `GET/PUT /api/v1/drivers/{driver}/cdl-license`   | A+Act|
| `dashboard.drivers.show.address`                 | `GET/PUT /api/v1/drivers/{driver}/address`       | A+Act|
| `dashboard.drivers.show.medicalcard`             | `GET/PUT /api/v1/drivers/{driver}/medical-card`  | A+Act|
| `dashboard.drivers.show.drugtest`                | `GET/PUT /api/v1/drivers/{driver}/drug-test`     | A+Act|
| `dashboard.drivers.show.mvr`                     | `GET/PUT /api/v1/drivers/{driver}/mvr`           | A+Act|
| `dashboard.drivers.show.todo`                    | `GET    /api/v1/drivers/{driver}/todo`           | A+Act|
| `dashboard.drivers.show.logs`                    | `GET    /api/v1/drivers/{driver}/logs`           | A+Act|

SPA paths: `/drivers`, `/drivers/new`, `/drivers/:id/{profile|license|...}`.

## Vehicles (`/api/v1/vehicles`)
Same shape as drivers — index/store/show/update/destroy plus sub-resources:
`profile`, `insurance`, `inspections`, `crashes`, `driver-history` (CRUD), `logs`.

## Insurance Vehicles (`/api/v1/insurance-vehicles`)
index/store/show/update/destroy + `profile` sub-resource.

## Services / Service Fields / Service Groups (admin)
- `/api/v1/services` — index/store/show/update/destroy + `update-status`
  - nested: `/api/v1/services/{service}/fields` (POST/PUT/DELETE)
- `/api/v1/service-fields` — index/store/show/update/destroy
- `/api/v1/service-groups` — index/store/show/update/destroy

MW: `A + R[admin,manager]`.

## Service Requests (user) — `/api/v1/service-requests`
| Old                                                | API                                                |
|----------------------------------------------------|----------------------------------------------------|
| `dashboard.servicerequest.history.index`           | `GET  /api/v1/service-requests`                    |
| `dashboard.servicerequest.history.show`            | `GET  /api/v1/service-requests/{request}`          |
| `dashboard.servicerequest.history.show.payments`   | `GET  /api/v1/service-requests/{request}/payments` |
| `dashboard.servicerequest.history.showpay` GET     | `GET  /api/v1/service-requests/{request}/pay`      |
| `dashboard.servicerequest.history.showpayprocess`  | `POST /api/v1/service-requests/{request}/pay`      |
| `dashboard.servicerequest.group`                   | `GET  /api/v1/service-requests/groups/{group}`     |
| `dashboard.servicerequest.show`                    | `GET  /api/v1/service-requests/groups/{group}/services/{service}` |
| `dashboard.servicerequest.store.request`           | `POST /api/v1/service-requests/groups/{group}/services/{service}` |

MW: `A+Act+R[driver,company]`.

## Request Manage (admin) — `/api/v1/admin/requests`
index/show/update/destroy + `update-status`, `update-fields`.
Plus: `GET /api/v1/admin/requests-overview` (was `admin.requests.index`).

MW: `A + R[admin,manager]`.

## Subscriptions
- User-facing: `/api/v1/subscription` — index/update + `cancel` + cards CRUD + `cards/{id}/make-primary`.
- Admin "Sub Manager": `/api/v1/admin/user-subscriptions` — index/store/show/update/destroy + nested `profile`, `user`, `company`, `send-onetime`, `send-payment-link`.
- Admin "Sub Plans": `/api/v1/admin/sub-plans` — CRUD.
- Admin "Sub Requests": `/api/v1/admin/sub-requests` — CRUD + `send-email`.
- Admin "Plan Fees": `/api/v1/admin/plan-fees` — index/store/show/update.

## Clearing House (`/api/v1/clearing-house`)
| Old                                            | API                                                |
|------------------------------------------------|----------------------------------------------------|
| `dashboard.clearinghouse.index`                | `GET  /api/v1/clearing-house`                      |
| `dashboard.clearinghouse.store`                | `POST /api/v1/clearing-house/queries`              |
| `dashboard.clearinghouse.registercompany`      | `GET  /api/v1/clearing-house/register-company`     |
| `dashboard.clearinghouse.buyqueries.index`     | `GET  /api/v1/clearing-house/buy-queries`          |
| `dashboard.clearinghouse.buyqueries.process`   | `POST /api/v1/clearing-house/buy-queries`          |
| `dashboard.clearinghouse.buyqueries.success`   | `GET  /api/v1/clearing-house/buy-queries/success/{order}` |

Plus existing balance-history sub-call surfaced as `GET /api/v1/clearing-house/balance-history`.

## Orders & Payments
- `GET  /api/v1/orders/{order}`
- `GET  /api/v1/orders/{order}/pay`
- `POST /api/v1/orders/{order}/pay`

## Notifications
- User: `GET /api/v1/notifications` — list (+ optional `PUT /api/v1/notifications/{id}/read`).
- Admin: `/api/v1/admin/notifications` — CRUD.

## To-Do (`/api/v1/todo`)
`index`, `company`, `vehicle`, `driver`, `show/{task}`.

## Search
`GET/POST /api/v1/search/global?q=…`.

## Documents
`GET /api/v1/documents`.

## Saferweb
`GET /api/v1/saferweb/inspections`, `/inspections/{id}`, `/crashes`, `/crashes/{id}`.

## Admin: Users / Settings / Gateways / Notifications Manager
- Users:   `/api/v1/admin/users` CRUD.
- Settings: `GET /api/v1/admin/settings`, `PUT /api/v1/admin/settings`.
- Gateways: `GET /api/v1/admin/gateways`.
- Notifications Manager: `/api/v1/admin/notifications-manage` CRUD.

## Files
- `GET /api/v1/files/{file}/download`
- `GET /api/v1/files/{file}` — metadata
- `POST /api/v1/files` — generic upload (multipart, returns id + url)

## References & Globals (NEW — needed by SPA forms)
- `GET /api/v1/references/states`
- `GET /api/v1/references/driver-types`
- `GET /api/v1/references/license-types`
- `GET /api/v1/references/license-endorsements`
- `GET /api/v1/references/vehicle-ownership-types`
- `GET /api/v1/references/vehicle-unit-types`
- `GET /api/v1/references/form-fields`
- `GET /api/v1/references/query-prices`
- `GET /api/v1/globals` — site settings, currency, role flags, feature toggles
