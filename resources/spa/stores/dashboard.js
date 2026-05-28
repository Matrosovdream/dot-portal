import { defineStore } from 'pinia';
import { api } from '@/api/client';

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
                const { data } = await api.get('/dashboard/home');
                const payload = data.data ?? data;
                this.role = payload.role;
                this.widgets = payload.widgets;
            } catch (e) {
                this.error = e.response?.data?.message ?? e.message;
            } finally {
                this.loading = false;
            }
        },
    },
});
