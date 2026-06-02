import { defineStore } from 'pinia';
import { authApi, bootstrapCsrf, unwrap } from '@/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        me: null,
        bootstrapped: false,
        loading: false,
    }),
    getters: {
        isAuthenticated: (s) => !!s.me,
        // Account fully activated (registration/onboarding complete). New
        // sign-ups come back is_active:false with a reg_step to finish.
        isActive: (s) => !!s.me?.is_active,
        isAdmin: (s) => !!s.me?.flags?.is_admin,
        isManager: (s) => !!s.me?.flags?.is_manager,
        isCompany: (s) => !!s.me?.flags?.is_company,
        isDriver: (s) => !!s.me?.flags?.is_driver,
    },
    actions: {
        async bootstrap() {
            if (this.bootstrapped) return;
            this.loading = true;
            try {
                await bootstrapCsrf();
                try {
                    this.me = unwrap(await authApi.me());
                } catch {
                    this.me = null;
                }
            } finally {
                this.bootstrapped = true;
                this.loading = false;
            }
        },
        async login(payload) {
            this.me = unwrap(await authApi.login(payload));
            this.bootstrapped = true;
            return this.me;
        },
        // Hydrate the session from an already-authenticated response
        // (register / one-time-login), bypassing bootstrap()'s short-circuit.
        setMe(user) {
            this.me = user;
            this.bootstrapped = true;
        },
        async logout() {
            try {
                await authApi.logout();
            } catch {
                /* already logged out / network — clear locally anyway */
            }
            this.me = null;
        },
    },
});
