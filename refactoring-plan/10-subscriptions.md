# Step 10 — Subscriptions, Plans, Plan Fees, Sub Requests, Sub Manager

Five related admin surfaces plus the user-facing subscription dashboard.

## User-facing (`/api/v1/subscription`)
Middleware: `auth:sanctum`, `user.isActive`, `hasRole:driver,company`.

| Method | Path                                          | Notes                       |
|--------|-----------------------------------------------|-----------------------------|
| GET    | `/`                                           | current plan + history      |
| PUT    | `/`                                           | change plan                 |
| POST   | `/cancel`                                     | cancel                      |
| GET    | `/cards`                                      | list payment cards          |
| POST   | `/cards`                                      | add card (tokenised)        |
| GET    | `/cards/{card}`                               | show                        |
| PUT    | `/cards/{card}`                               | update                      |
| POST   | `/cards/{card}/make-primary`                  | promote                     |
| DELETE | `/cards/{card}`                               | remove                      |

## Admin: Sub Manager (`/api/v1/admin/user-subscriptions`)
Middleware: `auth:sanctum`, `hasRole:admin,manager`.

| Method | Path                                                          |
|--------|---------------------------------------------------------------|
| GET    | `/`                                                           |
| POST   | `/`                                                           |
| GET    | `/{sub}`                                                      |
| PUT    | `/{sub}`                                                      |
| DELETE | `/{sub}`                                                      |
| PUT    | `/{sub}/profile`                                              |
| PUT    | `/{sub}/user`                                                 |
| PUT    | `/{sub}/company`                                              |
| POST   | `/{sub}/send-onetime`                                         |
| POST   | `/{sub}/send-payment-link`                                    |

## Admin: Sub Plans (`/api/v1/admin/sub-plans`)
Full CRUD.

## Admin: Sub Requests (`/api/v1/admin/sub-requests`)
Full CRUD + `POST /{req}/send-email`.

## Admin: Plan Fees (`/api/v1/admin/plan-fees`)
GET list / POST create / GET show / PUT update (no destroy — matches existing code).

## Controllers
Delegate to `SubscriptionUserActions`, `SubManagerActions`, `SubPlansActions`, `SubRequestsActions`, `PlanFeesActions`.

## FormRequests
One per write endpoint; reuse existing rules from the matching Blade controller method.

## Resources
- `SubscriptionResource` — current + plan + points + status.
- `SubscriptionPlanResource`, `SubscriptionCustomRequestResource`, `PlanFeeResource`.
- `PaymentCardResource` — `last4`, `brand`, `is_primary`, `expires_at`. Never expose raw card data.

## Payment card tokenisation
Card creation must go through the existing payment gateway abstraction (`App\Services\Payments\*`) — the API endpoint accepts an opaque token from the gateway's JS SDK, not raw PAN/CVV. SPA uses the gateway's hosted fields / iframe; we only persist the gateway's customer + payment-method ids.

## SPA pieces
- Stores: `subscription.ts`, `adminSubscriptions.ts`, `subPlans.ts`, `subRequests.ts`, `planFees.ts`.
- Views per surface:
  - User: `views/subscription/{IndexView,CardsView,CardFormView}.vue`.
  - Admin: `views/admin/subscriptions/`, `…/plans/`, `…/requests/`, `…/fees/`.
- Card-add flow embeds gateway hosted fields then submits the token.

## Tests — `tests/Feature/Api/V1/Subscriptions/`, `…/Admin/SubManager/`, `…/Admin/SubPlans/`, `…/Admin/SubRequests/`, `…/Admin/PlanFees/`
- User subscription: index, change plan, cancel.
- Cards: add (with mocked gateway), list, make-primary, delete.
- Admin sub-manager: CRUD + send-onetime (`Mail::fake`), send-payment-link.
- Plans / requests / fees: standard CRUD matrix.
- Role gates: company user → 403 on every admin route.

## Done when
- All endpoints respond JSON.
- SPA user can change plan + manage cards; admin can manage all five sections.
- `php artisan test tests/Feature/Api/V1/Subscriptions tests/Feature/Api/V1/Admin/SubManager tests/Feature/Api/V1/Admin/SubPlans tests/Feature/Api/V1/Admin/SubRequests tests/Feature/Api/V1/Admin/PlanFees` green.
