// Route table. Sections mirror the /api/v1 surface. `meta.roles` is enforced
// by the global guard (router/index.js); the sidebar (AppMenu) hides items by
// the same rule. Core sections have real views; the rest render ComingSoon
// until their screens are built out.

const ComingSoon = () => import('@/views/ComingSoon.vue');

/** A reachable placeholder route for a section that isn't fully built yet. */
function stub(path, name, title, roles) {
    return {
        path,
        name,
        component: ComingSoon,
        meta: { requiresAuth: true, title, roles, breadcrumb: [title] },
    };
}

const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/LoginView.vue'),
        meta: { requiresAuth: false, title: 'Sign in' },
    },
    // ----- Public auth flows (standalone, outside the app shell) -----
    {
        path: '/register',
        name: 'register',
        component: () => import('@/views/auth/RegisterView.vue'),
        meta: { requiresAuth: false, title: 'Create account' },
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('@/views/auth/ForgotPasswordView.vue'),
        meta: { requiresAuth: false, title: 'Forgot password' },
    },
    {
        path: '/reset-password/:token',
        name: 'reset-password',
        component: () => import('@/views/auth/ResetPasswordView.vue'),
        props: true,
        meta: { requiresAuth: false, title: 'Reset password' },
    },
    {
        path: '/login-onetime/:token',
        name: 'login-onetime',
        component: () => import('@/views/auth/OneTimeLoginView.vue'),
        props: true,
        meta: { requiresAuth: false, title: 'Signing in…' },
    },
    {
        // Email-verification landing. Needs an authenticated session, so it
        // sits outside the app shell but with requiresAuth so the guard
        // redirects unauthenticated hits to /login.
        path: '/verify-email',
        name: 'verify-email',
        component: () => import('@/views/auth/VerifyEmailView.vue'),
        meta: { requiresAuth: true, title: 'Verify email' },
    },
    {
        // Landing for authenticated-but-not-yet-activated accounts (the guard
        // funnels new sign-ups here instead of the app, which would 409).
        path: '/pending',
        name: 'onboarding-pending',
        component: () => import('@/views/auth/OnboardingPendingView.vue'),
        meta: { requiresAuth: true, title: 'Account pending' },
    },
    {
        path: '/',
        component: () => import('@/layout/AppLayout.vue'),
        meta: { requiresAuth: true },
        children: [
            { path: '', redirect: { name: 'dashboard' } },

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
                    roles: ['driver', 'company', 'admin', 'manager'],
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
                    roles: ['driver', 'company', 'admin', 'manager'],
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
                    roles: ['driver', 'company', 'admin', 'manager'],
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
                    roles: ['driver', 'company', 'admin', 'manager'],
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
                    roles: ['driver', 'company', 'admin', 'manager'],
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
                    roles: ['driver', 'company', 'admin', 'manager'],
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
                    roles: ['driver', 'company'],
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
                    roles: ['driver', 'company'],
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

            // ----- Operations (stubs) -----
            stub('insurance-vehicles', 'insurance-vehicles', 'Insurance Vehicles'),
            stub('clearing-house', 'clearing-house', 'Clearing House'),
            stub('todo', 'todo', 'To-Do'),
            stub('documents', 'documents', 'Documents'),
            stub('saferweb', 'saferweb', 'Saferweb', ['driver', 'company']),

            // ----- Billing (stubs) -----
            stub('subscription', 'subscription', 'Subscription', ['driver', 'company']),
            stub('orders', 'orders', 'Orders'),

            // ----- Administration (stubs) -----
            stub('admin/requests', 'admin.requests', 'Requests Manage', ['admin', 'manager']),
            stub('admin/services', 'admin.services', 'Services', ['admin', 'manager']),
            stub('admin/service-fields', 'admin.service-fields', 'Service Fields', ['admin', 'manager']),
            stub('admin/service-groups', 'admin.service-groups', 'Service Groups', ['admin', 'manager']),
            stub('admin/sub-plans', 'admin.sub-plans', 'Subscription Plans', ['admin', 'manager']),
            stub('admin/sub-requests', 'admin.sub-requests', 'Subscription Requests', ['admin', 'manager']),
            stub('admin/plan-fees', 'admin.plan-fees', 'Plan Fees', ['admin', 'manager']),
            stub('admin/user-subscriptions', 'admin.user-subscriptions', 'User Subscriptions', ['admin', 'manager']),
            stub('admin/notifications-manage', 'admin.notifications-manage', 'Notifications Manager', ['admin', 'manager']),

            // ----- System (stubs) -----
            stub('admin/users', 'admin.users', 'Users', ['admin']),
            stub('admin/settings', 'admin.settings', 'Settings', ['admin']),
            stub('admin/gateways', 'admin.gateways', 'Payment Gateways', ['admin']),

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
