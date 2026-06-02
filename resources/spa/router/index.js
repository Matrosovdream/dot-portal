import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import routes from './routes';

// Routes that an already-authenticated user should be bounced away from.
const GUEST_ONLY = ['login', 'register', 'forgot-password'];

// Authed-but-inactive users may still reach these (finish onboarding / verify).
const ONBOARDING_EXEMPT = ['onboarding-pending', 'verify-email'];

const router = createRouter({
    // The SPA owns the site root after the cutover (routes/web.php catch-all).
    history: createWebHistory('/'),
    routes,
    scrollBehavior: () => ({ top: 0 }),
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();
    if (!auth.bootstrapped) await auth.bootstrap();

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return { name: 'login', query: { next: to.fullPath } };
    }
    // Authenticated but not yet activated (just registered → is_active:false):
    // funnel into onboarding instead of the app, which would 409 on every call.
    if (auth.isAuthenticated && !auth.isActive && to.meta.requiresAuth && !ONBOARDING_EXEMPT.includes(to.name)) {
        return { name: 'onboarding-pending' };
    }
    // Already activated but sitting on the pending page → send to the dashboard.
    if (auth.isAuthenticated && auth.isActive && to.name === 'onboarding-pending') {
        return { name: 'dashboard' };
    }
    // Already signed in → keep them out of the guest-only auth pages.
    // reset-password / verify-email / login-onetime stay reachable in any state.
    if (GUEST_ONLY.includes(to.name) && auth.isAuthenticated) {
        return { name: auth.isActive ? 'dashboard' : 'onboarding-pending' };
    }
    if (to.meta.roles && !to.meta.roles.some((r) => auth.me?.flags?.[`is_${r}`])) {
        return { name: 'forbidden' };
    }
});

router.afterEach((to) => {
    document.title = to.meta?.title ? `${to.meta.title} · DOT Portal` : 'DOT Portal';
});

// Routes where a 401 is expected / handled locally — don't bounce to login.
const PUBLIC_AUTH_ROUTES = ['login', 'register', 'forgot-password', 'reset-password', 'login-onetime', 'verify-email'];

// Axios 401 interceptor → clear session and bounce to login.
window.addEventListener('auth:expired', () => {
    const auth = useAuthStore();
    auth.me = null;
    if (!PUBLIC_AUTH_ROUTES.includes(router.currentRoute.value.name)) {
        router.push({ name: 'login', query: { next: router.currentRoute.value.fullPath } });
    }
});

export default router;
