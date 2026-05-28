import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const routes = [
    {
        path: '/login',
        component: () => import('@/views/LoginView.vue'),
        meta: { requiresAuth: false, layout: 'auth' },
        name: 'login',
    },
    {
        path: '/',
        component: () => import('@/layouts/AppLayout.vue'),
        meta: { requiresAuth: true, layout: 'app' },
        children: [
            {
                path: '',
                redirect: { name: 'dashboard' },
            },
            {
                path: 'dashboard',
                name: 'dashboard',
                component: () => import('@/views/dashboard/DashboardView.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: 'ping',
                name: 'ping',
                component: () => import('@/views/PingView.vue'),
                meta: { requiresAuth: true },
            },
            {
                path: '403',
                name: '403',
                component: () => import('@/views/errors/Forbidden.vue'),
            },
            {
                path: ':pathMatch(.*)*',
                name: 'notfound',
                component: () => import('@/views/errors/NotFound.vue'),
            },
        ],
    },
];

const router = createRouter({
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
    if (to.name === 'login' && auth.isAuthenticated) {
        return { name: 'dashboard' };
    }
    if (to.meta.roles && !to.meta.roles.some((r) => auth.me?.flags?.[`is_${r}`])) {
        return { name: '403' };
    }
});

window.addEventListener('auth:expired', () => {
    const auth = useAuthStore();
    auth.me = null;
    if (router.currentRoute.value.name !== 'login') {
        router.push({ name: 'login', query: { next: router.currentRoute.value.fullPath } });
    }
});

export default router;
