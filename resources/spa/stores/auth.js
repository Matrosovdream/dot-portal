import { defineStore } from 'pinia';
import { api, bootstrapCsrf } from '@/api/client';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        me: null,
        bootstrapped: false,
        loading: false,
    }),
    getters: {
        isAuthenticated: (s) => !!s.me,
        isAdmin:   (s) => !!s.me?.flags?.is_admin,
        isManager: (s) => !!s.me?.flags?.is_manager,
        isCompany: (s) => !!s.me?.flags?.is_company,
        isDriver:  (s) => !!s.me?.flags?.is_driver,
    },
    actions: {
        async bootstrap() {
            if (this.bootstrapped) return;
            this.loading = true;
            try {
                await bootstrapCsrf();
                try {
                    const { data } = await api.get('/auth/me');
                    this.me = data.data ?? data;
                } catch (e) {
                    this.me = null;
                }
            } finally {
                this.bootstrapped = true;
                this.loading = false;
            }
        },
        async login(payload) {
            await bootstrapCsrf();
            const { data } = await api.post('/auth/login', payload);
            this.me = data.data ?? data;
            return this.me;
        },
        async logout() {
            try { await api.post('/auth/logout'); } catch (_) {}
            this.me = null;
        },
    },
});
