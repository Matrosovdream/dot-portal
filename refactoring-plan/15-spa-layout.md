# Step 15 — SPA Layout (Sakai shell, router, role menu, error pages)

Frontend-only step. Everything API-side already exists by now. This step builds the production navigation: role-aware menu, breadcrumbs, error boundaries, loading skeletons, 401/403/404/500 screens, and route guards.

## Router
`resources/spa/router/index.ts`:
```ts
const router = createRouter({
  history: createWebHistory('/'),
  routes,
  scrollBehavior: () => ({ top: 0 }),
});

router.beforeEach(async (to) => {
  const auth = useAuthStore();
  if (!auth.bootstrapped) await auth.bootstrap();
  if (to.meta.requiresAuth && !auth.me) return { name: 'login', query: { next: to.fullPath } };
  if (to.meta.roles && !to.meta.roles.some(r => auth.me?.flags[`is_${r}`])) return { name: '403' };
});
```

## Route modules
Split routes by section (`router/routes/drivers.ts`, `…/vehicles.ts`, etc.) and assemble in `routes.ts`. Each route declares `meta.requiresAuth`, `meta.roles`, `meta.breadcrumb`.

## Layout
- `layouts/AppLayout.vue` — Sakai shell: topbar + sidebar + main outlet.
- `layouts/AuthLayout.vue` — centered card for login/register/reset/verify.
- `layouts/BlankLayout.vue` — for the SPA 500/maintenance screen.

Use `meta.layout` on routes to pick the layout dynamically (one `<component :is="layout">` wrapper in `App.vue`).

## Menu — role-aware
`layouts/AppMenu.vue` reads `useAuthStore().me.flags` and `useGlobalsStore().featureFlags` to build the sidebar:
```ts
const items = computed(() => [
  group('Dashboard', [item('Home', '/')]),
  group('Operations', [
    item('Drivers',         '/drivers',          { roles: ['driver','company','admin','manager'] }),
    item('Vehicles',        '/vehicles',         { roles: ['driver','company','admin','manager'] }),
    item('Insurance',       '/insurance-vehicles'),
    item('Service Requests',         '/service-requests'),
    item('Clearing House',  '/clearing-house'),
    item('To-Do',           '/todo'),
    item('Documents',       '/documents'),
    item('Saferweb',        '/saferweb'),
  ]),
  group('Billing', [
    item('Subscription',    '/subscription',     { roles: ['driver','company'] }),
    item('Orders',          '/orders'),
  ]),
  group('Admin', { roles: ['admin','manager'] }, [
    item('Requests Manage', '/admin/requests'),
    item('Services',        '/admin/services'),
    item('Service Fields',  '/admin/service-fields'),
    item('Service Groups',  '/admin/service-groups'),
    item('Sub Plans',       '/admin/sub-plans'),
    item('Sub Requests',    '/admin/sub-requests'),
    item('Plan Fees',       '/admin/plan-fees'),
    item('User Subs',       '/admin/user-subscriptions'),
    item('Notifications',   '/admin/notifications-manage'),
  ]),
  group('System', { roles: ['admin'] }, [
    item('Users',           '/admin/users'),
    item('Settings',        '/admin/settings'),
    item('Gateways',        '/admin/gateways'),
  ]),
]);
```

## Error screens
- `views/errors/Forbidden.vue` (403) — "you don't have access" + back button.
- `views/errors/NotFound.vue` (404).
- `views/errors/ServerError.vue` (500) — used by global axios interceptor.
- Global error handler: `app.config.errorHandler` and unhandled promise rejection both route to ServerError.

## UX polish
- Skeleton loaders: PrimeVue `Skeleton` inside each view's `loading` slot.
- Toast notifications: PrimeVue `<Toast />` in `AppLayout`; axios success/failure helpers push toasts.
- Confirm dialogs for destructive actions (`ConfirmDialog`).
- Dark mode toggle stored in `localStorage`; class on `<html>`.

## Auth integration
- The "auth:expired" custom event from step 01's axios interceptor → auth store clears `me` → router pushes `/login?next=…`.
- 403 from API → push `/403` (only if not already there).

## Tests (frontend smoke)
This step is too UI-heavy for unit tests to be worth it. Required check:
- `npm run build` exits 0.
- Manual smoke list (also documented in `progress.md`):
  - login → home for each role
  - role-restricted menu items hidden
  - 403 page on forbidden route
  - dark mode toggles
  - logout returns to /login

## Done when
- SPA navigation, menu, layouts, error pages are production-quality.
- All sections from steps 02–14 are reachable from the menu (or appropriately hidden by role).
- `progress.md` step 15 checked with the manual smoke checklist filled in.
