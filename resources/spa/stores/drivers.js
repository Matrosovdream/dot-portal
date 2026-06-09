import { defineStore } from 'pinia';
import { driversApi, errorMessage } from '@/api';

export const useDriversStore = defineStore('drivers', {
    state: () => ({
        items: [],
        meta: null,
        loading: false,
        error: null,
        filters: { q: '', status: null, user_id: null, page: 1, per_page: 25 },
    }),
    actions: {
        async fetch(overrides = {}) {
            Object.assign(this.filters, overrides);
            this.loading = true;
            this.error = null;
            try {
                const params = {
                    page: this.filters.page,
                    per_page: this.filters.per_page,
                };
                if (this.filters.q) params.q = this.filters.q;
                if (this.filters.status) params.status = this.filters.status;
                if (this.filters.user_id) params.user_id = this.filters.user_id;

                const body = await driversApi.list(params);
                this.items = body.data ?? [];
                this.meta = body.meta ?? null;
            } catch (e) {
                this.error = errorMessage(e, 'Failed to load drivers');
            } finally {
                this.loading = false;
            }
        },
    },
});
