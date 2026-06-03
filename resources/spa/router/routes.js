// Route table. Sections mirror the /api/v1 surface. `meta.roles` is enforced
// by the global guard (router/index.js); the sidebar (AppMenu) hides items by
// the same rule. Core sections have real views; the rest render ComingSoon
// until their screens are built out.

const ComingSoon = () => import('@/views/ComingSoon.vue');
const CrudList = () => import('@/views/crud/CrudListView.vue');
const CrudForm = () => import('@/views/crud/CrudFormView.vue');

// ----- Page templates (layouts). Each is a route parent with a nested
// <router-view/>; the matched layout wraps its child pages. -----
const WebLayout = () => import('@/layout/WebLayout.vue');   // public/marketing chrome
const AuthLayout = () => import('@/layout/AuthLayout.vue'); // split-screen auth chrome
const AppLayout = () => import('@/layout/AppLayout.vue');   // authenticated portal shell

/** A reachable placeholder route for a section that isn't fully built yet. */
function stub(path, name, title, roles, note) {
    return {
        path,
        name,
        component: ComingSoon,
        meta: { requiresAuth: true, title, roles, breadcrumb: [title], note },
    };
}

/**
 * Generate list (+ optional create/edit) routes for a config-driven CRUD
 * section. `section` must match a key in views/crud/configs.js; the generic
 * views read route.meta.section to resolve their config.
 */
function crud(path, section, title, roles, { create = true, edit = true } = {}) {
    const base = { requiresAuth: true, section, roles };
    const out = [
        { path, name: section, component: CrudList, meta: { ...base, title, breadcrumb: [title] } },
    ];
    if (create) {
        out.push({
            path: `${path}/new`,
            name: `${section}.create`,
            component: CrudForm,
            meta: { ...base, title: `New ${title}`, breadcrumb: [title, 'New'] },
        });
    }
    if (edit) {
        out.push({
            path: `${path}/:id`,
            name: `${section}.edit`,
            component: CrudForm,
            props: true,
            meta: { ...base, title: `Edit ${title}`, breadcrumb: [title, 'Edit'] },
        });
    }
    return out;
}

const routes = [
    // Bare root → dashboard. Declared first so it deterministically owns '/'
    // (otherwise a layout parent below, all sharing path '/', would match the
    // root as an empty shell). The guard then bounces guests on to /login.
    { path: '/', redirect: { name: 'dashboard' } },

    // ===== Web template — public/marketing pages (not auth, not the portal) =====
    {
        path: '/',
        component: WebLayout,
        children: [
            {
                path: 'welcome',
                name: 'web-home',
                component: () => import('@/views/web/HomeView.vue'),
                meta: { requiresAuth: false, title: 'Welcome' },
            },
        ],
    },

    // ===== Auth template — sign-in / registration / recovery flows =====
    {
        path: '/',
        component: AuthLayout,
        children: [
            {
                path: 'login',
                name: 'login',
                component: () => import('@/views/auth/LoginView.vue'),
                meta: { requiresAuth: false, title: 'Sign in' },
            },
            {
                path: 'register',
                name: 'register',
                component: () => import('@/views/auth/RegisterView.vue'),
                meta: { requiresAuth: false, title: 'Create account' },
            },
            {
                path: 'forgot-password',
                name: 'forgot-password',
                component: () => import('@/views/auth/ForgotPasswordView.vue'),
                meta: { requiresAuth: false, title: 'Forgot password' },
            },
            {
                path: 'reset-password/:token',
                name: 'reset-password',
                component: () => import('@/views/auth/ResetPasswordView.vue'),
                props: true,
                meta: { requiresAuth: false, title: 'Reset password' },
            },
            {
                path: 'login-onetime/:token',
                name: 'login-onetime',
                component: () => import('@/views/auth/OneTimeLoginView.vue'),
                props: true,
                meta: { requiresAuth: false, title: 'Signing in…' },
            },
            {
                // Email-verification landing. requiresAuth so the guard
                // redirects unauthenticated hits to /login.
                path: 'verify-email',
                name: 'verify-email',
                component: () => import('@/views/auth/VerifyEmailView.vue'),
                meta: { requiresAuth: true, title: 'Verify email' },
            },
            {
                // Landing for authenticated-but-not-yet-activated accounts (the
                // guard funnels new sign-ups here instead of the app, which 409s).
                path: 'pending',
                name: 'onboarding-pending',
                component: () => import('@/views/auth/OnboardingPendingView.vue'),
                meta: { requiresAuth: true, title: 'Account pending' },
            },
        ],
    },

    // ===== Dashboard template — the authenticated portal shell =====
    {
        path: '/',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: () => import('@/views/dashboard/DashboardView.vue'),
                meta: { requiresAuth: true, title: 'Dashboard', breadcrumb: ['Dashboard'] },
            },

            // ----- Profile -----
            {
                path: 'profile',
                name: 'profile',
                component: () => import('@/views/profile/ProfileView.vue'),
                meta: {
                    requiresAuth: true,
                    roles: ['driver', 'company'],
                    title: 'My Profile',
                    breadcrumb: ['My Profile'],
                },
            },

            // ----- Drivers -----
            {
                path: 'drivers',
                name: 'drivers',
                component: () => import('@/views/drivers/DriverListView.vue'),
                meta: {
                    requiresAuth: true,
                    roles: ['company', 'admin'],
                    title: 'Drivers',
                    breadcrumb: ['Drivers'],
                },
            },
            {
                path: 'drivers/new',
                name: 'drivers.create',
                component: () => import('@/views/drivers/DriverFormView.vue'),
                meta: {
                    requiresAuth: true,
                    roles: ['company', 'admin'],
                    title: 'New Driver',
                    breadcrumb: ['Drivers', 'New'],
                },
            },
            {
                path: 'drivers/:id',
                name: 'drivers.edit',
                component: () => import('@/views/drivers/DriverFormView.vue'),
                props: true,
                meta: {
                    requiresAuth: true,
                    roles: ['company', 'admin'],
                    title: 'Edit Driver',
                    breadcrumb: ['Drivers', 'Edit'],
                },
            },

            // ----- Vehicles -----
            {
                path: 'vehicles',
                name: 'vehicles',
                component: () => import('@/views/vehicles/VehicleListView.vue'),
                meta: {
                    requiresAuth: true,
                    roles: ['company', 'admin'],
                    title: 'Vehicles',
                    breadcrumb: ['Vehicles'],
                },
            },
            {
                path: 'vehicles/new',
                name: 'vehicles.create',
                component: () => import('@/views/vehicles/VehicleFormView.vue'),
                meta: {
                    requiresAuth: true,
                    roles: ['company', 'admin'],
                    title: 'New Vehicle',
                    breadcrumb: ['Vehicles', 'New'],
                },
            },
            {
                path: 'vehicles/:id',
                name: 'vehicles.edit',
                component: () => import('@/views/vehicles/VehicleFormView.vue'),
                props: true,
                meta: {
                    requiresAuth: true,
                    roles: ['company', 'admin'],
                    title: 'Edit Vehicle',
                    breadcrumb: ['Vehicles', 'Edit'],
                },
            },

            // ----- Service Requests -----
            {
                path: 'service-requests',
                name: 'service-requests',
                component: () => import('@/views/serviceRequests/ServiceRequestListView.vue'),
                meta: {
                    requiresAuth: true,
                    roles: ['company'],
                    title: 'Service Requests',
                    breadcrumb: ['Service Requests'],
                },
            },
            {
                path: 'service-requests/new',
                name: 'service-requests.create',
                component: () => import('@/views/serviceRequests/ServiceRequestFormView.vue'),
                meta: {
                    requiresAuth: true,
                    roles: ['company'],
                    title: 'New Service Request',
                    breadcrumb: ['Service Requests', 'New'],
                },
            },

            // ----- Notifications (bell target) -----
            {
                path: 'notifications',
                name: 'notifications',
                component: () => import('@/views/NotificationsView.vue'),
                meta: { requiresAuth: true, title: 'Notifications', breadcrumb: ['Notifications'] },
            },

            // ----- Operations -----
            ...crud('insurance-vehicles', 'insurance-vehicles', 'Insurance Vehicles', ['company', 'admin']),
            ...crud('documents', 'documents', 'Documents', ['driver', 'company', 'admin'], { create: false, edit: false }),
            {
                path: 'todo',
                name: 'todo',
                component: () => import('@/views/todo/TodoView.vue'),
                meta: { requiresAuth: true, roles: ['driver', 'company', 'admin'], title: 'To-Do', breadcrumb: ['To-Do'] },
            },
            {
                path: 'saferweb',
                name: 'saferweb',
                component: () => import('@/views/saferweb/SaferwebView.vue'),
                meta: { requiresAuth: true, roles: ['company'], title: 'SAFER Web', breadcrumb: ['SAFER Web'] },
            },
            stub('clearing-house', 'clearing-house', 'Clearing House', ['company', 'admin'],
                "Clearing House isn't available in the new portal yet — its backend API hasn't been built (refactor step 11). It will appear here once that lands."),

            // ----- Billing -----
            {
                path: 'subscription',
                name: 'subscription',
                component: () => import('@/views/subscription/SubscriptionView.vue'),
                meta: { requiresAuth: true, roles: ['company'], title: 'Subscription', breadcrumb: ['Subscription'] },
            },
            {
                path: 'orders',
                name: 'orders',
                component: () => import('@/views/orders/OrdersView.vue'),
                meta: { requiresAuth: true, roles: ['admin'], title: 'Orders', breadcrumb: ['Orders'] },
            },

            // ----- Administration (admin / manager) -----
            {
                path: 'admin/requests',
                name: 'admin.requests',
                component: () => import('@/views/admin/RequestManageView.vue'),
                meta: { requiresAuth: true, roles: ['admin', 'manager'], title: 'Requests Manage', breadcrumb: ['Requests Manage'] },
            },
            ...crud('admin/services', 'admin.services', 'Services', ['admin', 'manager']),
            ...crud('admin/service-fields', 'admin.service-fields', 'Service Fields', ['admin']),
            ...crud('admin/service-groups', 'admin.service-groups', 'Service Groups', ['admin']),
            ...crud('admin/sub-plans', 'admin.sub-plans', 'Subscription Plans', ['admin']),
            ...crud('admin/sub-requests', 'admin.sub-requests', 'Subscription Requests', ['admin']),
            ...crud('admin/plan-fees', 'admin.plan-fees', 'Plan Fees', ['admin']),
            ...crud('admin/user-subscriptions', 'admin.user-subscriptions', 'User Subscriptions', ['admin']),
            ...crud('admin/notifications-manage', 'admin.notifications-manage', 'Notifications', ['admin']),

            // ----- System (admin) -----
            ...crud('admin/users', 'admin.users', 'Users', ['admin']),
            {
                path: 'admin/settings',
                name: 'admin.settings',
                component: () => import('@/views/admin/SettingsView.vue'),
                meta: { requiresAuth: true, roles: ['admin'], title: 'Settings', breadcrumb: ['Settings'] },
            },
            ...crud('admin/gateways', 'admin.gateways', 'Payment Gateways', ['admin'], { create: false, edit: false }),

            // ----- Error pages -----
            {
                path: '403',
                name: 'forbidden',
                component: () => import('@/views/errors/Forbidden.vue'),
                meta: { requiresAuth: true, title: 'Forbidden' },
            },
            {
                path: '500',
                name: 'server-error',
                component: () => import('@/views/errors/ServerError.vue'),
                meta: { requiresAuth: true, title: 'Server Error' },
            },
            {
                path: ':pathMatch(.*)*',
                name: 'not-found',
                component: () => import('@/views/errors/NotFound.vue'),
                meta: { requiresAuth: true, title: 'Not Found' },
            },
        ],
    },
];

export default routes;
