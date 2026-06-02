import { defineStore } from 'pinia';
import { dashboardApi, unwrap, errorMessage } from '@/api';

export const useDashboardStore = defineStore('dashboard', {
    state: () => ({
        role: null,
        widgets: null,
        loading: false,
        error: null,
    }),
    actions: {
        async load() {
            this.loading = true;
            this.error = null;
            try {
                const payload = unwrap(await dashboardApi.home());
                this.role = payload.role;
                this.widgets = payload.widgets;
            } catch (e) {
                this.error = errorMessage(e, 'Failed to load dashboard');
            } finally {
                this.loading = false;
            }
        },
    },
});
