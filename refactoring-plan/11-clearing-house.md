# Step 11 — Clearing House

Drug & alcohol clearinghouse module: register company, run queries against driver records, buy query packs, view balance/history.

## Endpoints (`/api/v1/clearing-house`)
Middleware: `auth:sanctum`, `user.isActive`. Some routes restricted to `hasRole:driver,company`; admin reads everything.

| Method | Path                                       | Notes                                                |
|--------|--------------------------------------------|------------------------------------------------------|
| GET    | `/`                                        | dashboard view-model (balance, last queries, links)  |
| POST   | `/queries`                                 | submit a query for a driver / list                   |
| GET    | `/queries`                                 | history list                                         |
| GET    | `/queries/{query}`                         | detail                                               |
| GET    | `/balance-history`                         | paginated balance ledger                             |
| GET    | `/register-company`                        | form data for registering with the upstream API      |
| POST   | `/register-company`                        | submit registration                                  |
| GET    | `/buy-queries`                             | pack catalogue + prices (`RefQueryPrice`)            |
| POST   | `/buy-queries`                             | initiate buy; returns order id + redirect/pay payload|
| GET    | `/buy-queries/success/{order}`             | post-payment state                                   |

## Controller
`ClearingHouseController` + sub-controllers (`ClearingHouseQueryController`, `ClearingHouseBalanceController`, `ClearingHouseRegisterController`, `ClearingHouseBuyQueriesController`). Delegate to existing `ClearingHouseActions` and `RefQueryPrice` repository.

## FormRequests
- `RegisterCompanyClearingHouseRequest`.
- `RunQueryRequest` — driver ids / list params.
- `BuyQueriesRequest` — pack id, payment method.

## Resources
- `ClearingHouseDashboardResource` — balance, recent queries, registration status.
- `QueryResource`, `BalanceHistoryResource`, `QueryPriceResource`, `BuyOrderResource`.

## SPA pieces
- `stores/clearingHouse.ts`.
- Views: `ClearingHouseDashboard.vue`, `QueriesView.vue`, `BalanceHistoryView.vue`, `RegisterCompanyView.vue`, `BuyQueriesView.vue`, `BuyQueriesSuccessView.vue`.
- The buy flow opens the SPA's payment component, then redirects to `success/{order}` once the order is paid (works the same as step 12's order pay flow).

## Tests — `tests/Feature/Api/V1/ClearingHouse/`
- `DashboardTest` — returns balance + recent queries.
- `QueriesTest` — submit query (mock upstream HTTP — `Http::fake`), list, show.
- `BalanceHistoryTest` — paginated.
- `RegisterCompanyTest` — submit, persisted; validation.
- `BuyQueriesTest` — pack list shape; buy creates order with correct totals (use seeded `RefQueryPrice`).
- Role gates: drivers can buy/list own; admin sees all.

## Done when
- All clearing-house screens work from the SPA, mocked upstream returns acceptable.
- `php artisan test tests/Feature/Api/V1/ClearingHouse` green.
