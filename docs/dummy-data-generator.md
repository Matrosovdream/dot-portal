# Dummy Data Generator

Generates realistic, **synthetic** business data for dev/demo/testing — companies
and their drivers, vehicles, subscriptions, service requests, orders, tasks and
notifications. Modelled on the Freshdesk import generator pattern
(Command → Service → per-entity Generators → Repos → Models), but self-contained
(no external system / external ids).

## Usage

Run inside the app container (PHP ≥ 8.4, Postgres):

```bash
make dev-seed                 # reference + base data first (roles, states, services, plans…)
make dev-shell                # or: docker exec -it dotportal-workspace sh
php artisan dummy:generate    # generate dummy business data

# Options
php artisan dummy:generate --companies=20   # how many company accounts
php artisan dummy:generate --seed=42        # reproducible Faker output
php artisan dummy:generate --fresh          # wipe existing dummy data, then regenerate
```

> Requires the reference seeders to have run first (services, subscription plans,
> ref statuses, license/vehicle types, states). Generators that depend on missing
> reference data skip gracefully rather than error.

## What it creates (per run, dependency order)

| Step | Entity | Notes |
|------|--------|-------|
| 1 | Company users | `User` (role=company) + `UserCompany`; first few from curated anchors, rest via Faker |
| 2 | Drivers | via `DriverRepo` (creates the driver login `User` too) + address, license, medical card |
| 3 | Vehicles | per company, assigned to a company driver, + MVR (+ insurance link when seeded) |
| 4 | Payment cards | `UserPaymentCard` per company (first = primary) |
| 5 | Subscriptions | `UserSubscription` on the plan matching fleet size + months of `UserPaymentHistory` |
| 6 | Service requests | `Request` against the seeded services catalogue + one history row |
| 7 | Orders | `OrderRepo::createWithPayload` with line items + an `OrderPayment` |
| 8 | Tasks / notifications | `UserTask` (+ generated `unique_code`) and `Notification` |

All writes go through the existing **Repos / models** (not raw inserts) so business
rules and observers fire, exactly like the app does.

## Idempotency & the dummy marker

Every dummy record is reachable from a **dummy user**, identified solely by email
domain `@dummy.dotportal.test` (see `App\Services\DummyData\DummyConfig`). This makes
the data safe to find and remove on a shared dev DB.

- Re-running **without** `--fresh` is safe: entities with a natural key
  (`updateOrCreate` / `firstOrCreate`) update in place; orders and tasks/notifications
  (no natural key) **top up** to their target count rather than duplicating.
- `--fresh` deletes only dummy data (child rows first, then parents, then the users),
  scoped to the marker domain, inside a transaction.

Default login: any company account, e.g. `dummy+co-1@dummy.dotportal.test` /
password `password`.

## Layout

```
app/Console/Commands/DummyDataGenerateCommand.php   # php artisan dummy:generate
app/Services/DummyData/
    DummyConfig.php                 # marker domain, role ids, default volumes
    AssignmentPicker.php            # loads reference id pools + dummy-entity lookups
    DummyDataGeneratorService.php   # orchestrator: run() + wipe()
    Generators/*.php                # one per entity
tests/DummyData/dotportal/companies.json   # curated anchor companies
```

To change volumes, edit the range constants in `DummyConfig`. To add curated
companies, append to `companies.json` (used for the first N companies).
