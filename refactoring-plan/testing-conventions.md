# Testing Conventions

Every step ends with **green Feature tests** for every endpoint it introduces. No green tests → step is not done.

## Where tests live
```
tests/Feature/Api/V1/
  Auth/
    LoginTest.php
    RegisterTest.php
    PasswordResetTest.php
    SessionTest.php
  Profile/
  Drivers/
    DriverIndexTest.php
    DriverCrudTest.php
    DriverSubResourcesTest.php
  Vehicles/
  InsuranceVehicles/
  Services/
  ServiceFields/
  ServiceGroups/
  ServiceRequests/
  AdminRequests/
  Subscriptions/
  ClearingHouse/
  Orders/
  Notifications/
  Todo/
  Search/
  Documents/
  Saferweb/
  Admin/
    UsersTest.php
    SettingsTest.php
    GatewaysTest.php
    NotificationsManageTest.php
    SubPlansTest.php
    SubRequestsTest.php
    SubManagerTest.php
    PlanFeesTest.php
  References/
  Files/
```

## Shared trait
Extend the existing `Tests\Feature\Traits\EntityTestable` with an API-shaped sibling:

```php
// tests/Feature/Traits/ApiEntityTestable.php
trait ApiEntityTestable {
    protected $createdRecords = [];
    protected $model;
    protected $routes;          // keys: index, store, show, update, destroy
    protected $jsonStructure;   // resource shape for assertJsonStructure()

    protected function api()
    {
        return $this->actingAs($this->user, 'sanctum');
    }

    protected function assertIndexShape($response): void
    {
        $response->assertOk()->assertJsonStructure([
            'data' => ['*' => $this->jsonStructure],
            'meta' => ['current_page', 'per_page', 'total'],
        ]);
    }

    protected function storeJson(array $values): \Illuminate\Testing\TestResponse
    {
        return $this->api()->postJson($this->getRoute('store'), $values);
    }

    protected function updateJson($id, array $values): \Illuminate\Testing\TestResponse
    {
        return $this->api()->putJson($this->getRouteFor('update', $id), $values);
    }

    protected function destroyJson($id): \Illuminate\Testing\TestResponse
    {
        return $this->api()->deleteJson($this->getRouteFor('destroy', $id));
    }

    // … plus deleteAllRecords(), findRecord(), getRoute() identical to EntityTestable
}
```

## What every section test must cover
For each endpoint:
1. **Auth gate** — unauthenticated → `401`.
2. **Role gate** — authenticated-but-wrong-role → `403`.
3. **Happy path** — correct role, valid payload → `200`/`201` + JSON structure assertion.
4. **Validation** — missing required field → `422` + `errors.{field}`.
5. **Not-found** — bogus id → `404`.
6. **Persistence** (for write endpoints) — DB row exists / is updated / is gone.

## Auth in tests
- Use `Sanctum::actingAs($user, ['*'])` for protected endpoints.
- For login/register tests, hit the actual endpoints — don't shortcut with `actingAs`.
- For role tests, seed one fixture user per role: `adminUser`, `managerUser`, `companyUser`, `driverUser`. Reuse via a `RoleFixtures` trait.

## Running
```bash
# the whole API suite
php artisan test --testsuite=Feature --filter=Api

# one section, e.g. drivers
php artisan test tests/Feature/Api/V1/Drivers

# one file
php artisan test tests/Feature/Api/V1/Drivers/DriverCrudTest.php
```

## Definition of done (per step)
- [ ] All endpoints in the step return the documented JSON shape.
- [ ] Route file diff is minimal, sectioned, and role-gated.
- [ ] No `dd()`, `Log::info()` debug, or `dump()` left behind.
- [ ] FormRequest + Resource exist for every write/read endpoint.
- [ ] Tests cover auth/role/happy/validation/404 for every endpoint.
- [ ] `php artisan test --filter=<StepFilter>` runs **green**.
- [ ] Step is checked off in `progress.md` with the test output snippet.
