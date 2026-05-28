# Step 02 — Auth & Session API

Replace Breeze's web auth controllers with JSON endpoints under `/api/v1/auth`. Keep the existing `User`, password reset, and email verification machinery — only the HTTP layer changes.

## Endpoints
| Method | Path                                       | Middleware              |
|--------|--------------------------------------------|-------------------------|
| POST   | `/api/v1/auth/login`                       | guest                   |
| POST   | `/api/v1/auth/logout`                      | auth:sanctum            |
| POST   | `/api/v1/auth/register`                    | guest                   |
| POST   | `/api/v1/auth/register/remove`             | guest                   |
| POST   | `/api/v1/auth/password/email`              | guest, throttle:6,1     |
| POST   | `/api/v1/auth/password/reset`              | guest                   |
| PUT    | `/api/v1/auth/password`                    | auth:sanctum            |
| POST   | `/api/v1/auth/password/confirm`            | auth:sanctum            |
| GET    | `/api/v1/auth/email/verify`                | auth:sanctum            |
| POST   | `/api/v1/auth/email/verify/resend`         | auth:sanctum, throttle:6,1 |
| GET    | `/api/v1/auth/email/verify/{id}/{hash}`    | signed, throttle:6,1    |
| GET    | `/api/v1/auth/login-onetime/{token}`       | guest                   |
| POST   | `/api/v1/auth/retrieve-usdot`              | auth:sanctum            |
| GET    | `/api/v1/auth/me`                          | auth:sanctum            |

## Implementation

### 2.1 Controllers
Move Breeze controllers under `App\Http\Controllers\Api\V1\Auth\`. Each method:
- accepts a FormRequest,
- delegates to the existing service / action,
- returns JSON (no redirects, no `view()`).

Example — `LoginController@store`:
```php
public function store(LoginRequest $request)
{
    $request->authenticate();      // existing trait logic, just keep
    $request->session()->regenerate();
    return new UserResource($request->user()->load('roles'));
}
```

### 2.2 FormRequests
- `LoginRequest`           — `email|required|email`, `password|required`
- `RegisterRequest`        — match current Breeze rules
- `PasswordEmailRequest`   — `email|required|email`
- `PasswordResetRequest`   — `token|required`, `email|required|email`, `password|required|confirmed`
- `PasswordUpdateRequest`  — `current_password|required`, `password|required|confirmed`
- `RetrieveUsdotRequest`   — `usdot|required|numeric`

### 2.3 Resource
`UserResource`:
```php
'id', 'firstname', 'lastname', 'fullname', 'email', 'phone',
'is_active', 'reg_step',
'roles' => $this->whenLoaded('roles', fn() => $this->roles->pluck('slug')),
'flags' => [
    'is_admin'   => $this->isAdmin(),
    'is_manager' => $this->isManager(),
    'is_company' => $this->isCompany(),
    'is_driver'  => $this->isDriver(),
],
```

### 2.4 `/auth/me` bootstrap
Returns the logged-in user (loaded with roles) **or** `401`. The SPA calls this on every cold load to learn its session state.

### 2.5 Logout
```php
Auth::guard('web')->logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
return response()->noContent();
```

### 2.6 Email verification flow
- `GET /email/verify` → returns `{ verified: bool, email: string }`.
- `POST /email/verify/resend` → sends mail, returns `204`.
- `GET /email/verify/{id}/{hash}` → keep signed middleware. On success: mark verified, return JSON `{ verified: true }`. The SPA route `/verify-email` polls or fetches `/auth/me`.

### 2.7 Retire web auth routes
Strip `routes/auth.php` of the Breeze pages, keep nothing in `routes/web.php` for auth. The Blade login/register pages are dead at step 17.

### 2.8 SPA pieces (this step)
- `stores/auth.ts` — `me`, `login(payload)`, `logout()`, `bootstrap()` (calls `/sanctum/csrf-cookie` then `/auth/me`).
- Router guard: routes with `meta.requiresAuth` push to `/login` when `auth.me` is null.
- Views: `LoginView.vue`, `RegisterView.vue`, `ForgotPasswordView.vue`, `ResetPasswordView.vue`, `VerifyEmailView.vue`, `LoginOnetimeView.vue`. All use PrimeVue `InputText`, `Password`, `Button`. Error mapping: read `error.response.data.errors` and bind per-field.

## Tests — `tests/Feature/Api/V1/Auth/`

- `LoginTest`
  - `test_login_succeeds_with_valid_credentials` → 200 + user JSON + session is authenticated.
  - `test_login_fails_with_wrong_password` → 422.
  - `test_login_throttle_after_too_many_attempts` → 429.
- `LogoutTest` — authenticated user logs out, then `GET /auth/me` returns 401.
- `RegisterTest` — create user, verify row, returns 201 + user shape.
- `MeTest` — guest → 401; authenticated → returns user with roles + flags.
- `PasswordResetTest` — request link, verify mail dispatched, reset with valid token, log in.
- `EmailVerificationTest` — unverified user, send notification, hit signed URL, verified flag flips.
- `OneTimeLoginTest` — valid token logs the user in; invalid token returns 404.
- `RetrieveUsdotTest` — happy path + 422 on bad input.

All tests use `RefreshDatabase` (or rollback transactions) and `Sanctum::actingAs` for the `auth:sanctum` ones.

## Done when
- All routes return JSON in every code path (no `redirect()` left in any Auth controller method).
- The SPA `/login` form successfully logs a seeded user in against the running app and routes to `/`.
- `php artisan test tests/Feature/Api/V1/Auth` is green.
