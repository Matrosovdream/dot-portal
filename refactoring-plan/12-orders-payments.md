# Step 12 — Orders & Payments

Orders are the universal billing record (subscription change, service request fee, clearing-house pack). Already covered partly in steps 09–11 — this step pulls them into a coherent section.

## Endpoints (`/api/v1/orders`)
Middleware: `auth:sanctum`, `user.isActive`. Admin sees all; user sees own.

| Method | Path                       | Notes                                |
|--------|----------------------------|--------------------------------------|
| GET    | `/`                        | (admin only) full list, filters      |
| GET    | `/{order}`                 | detail                               |
| GET    | `/{order}/pay`             | payment form (cards, gateways, total)|
| POST   | `/{order}/pay`             | process payment                      |
| GET    | `/{order}/payments`        | payments history for this order      |

## Controller
`OrderController`, `OrderPaymentController`. Delegate to existing `OrderActions` + `App\Services\Payments\*` services.

## FormRequests
- `PayOrderRequest` — `gateway`, `card_id|nullable`, fields the gateway needs.

## Resources
- `OrderResource` — items, total, currency, status, links.
- `OrderItemResource`, `OrderPaymentResource`.

## Payment service abstraction
- Wrap the existing gateway logic behind a `PaymentGatewayInterface` and a registry — `gateways.authorize`, `gateways.stripe`, etc.
- `POST /{order}/pay` returns either:
  - `{ status: "succeeded", payment_id }` — done.
  - `{ status: "requires_action", action: { type: "redirect"|"sca", url|payload } }` — SPA performs the action then re-polls `/{order}`.

## SPA pieces
- `stores/orders.ts`.
- Views: `OrderDetailView.vue`, `OrderPayView.vue`. The pay view is reused from service-request pay, sub-plan change, and clearing-house buy flows — make it a `<PayOrderForm :orderId />` component.

## Tests — `tests/Feature/Api/V1/Orders/`
- `OrderShowTest` — owner sees own; foreign user → 403/404.
- `OrderPayTest` — happy path with a faked gateway returns `succeeded`; SCA case returns `requires_action`.
- `PaymentRecordedTest` — after pay, `OrderPayment` row exists + order status flipped.
- `RoleGateTest` — only admin/manager can hit the global list.

## Done when
- All order/pay flows hit JSON only; no Blade pay views remain in use.
- `php artisan test tests/Feature/Api/V1/Orders` green.
