import { defineStore } from 'pinia';
import { serviceRequestsApi, errorMessage } from '@/api';

export const useServiceRequestsStore = defineStore('serviceRequests', {
    state: () => ({
        items: [],
        meta: null,
        loading: false,
        error: null,
        filters: { page: 1, per_page: 25 },
    }),
    actions: {
        async fetch(overrides = {}) {
            Object.assign(this.filters, overrides);
            this.loading = true;
            this.error = null;
            try {
                const body = await serviceRequestsApi.list({ ...this.filters });
                this.items = body.data ?? [];
                this.meta = body.meta ?? null;
            } catch (e) {
                this.error = errorMessage(e, 'Failed to load service requests');
            } finally {
                this.loading = false;
            }
        },
    },
});
