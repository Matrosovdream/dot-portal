import { defineStore } from 'pinia';
import { vehiclesApi, errorMessage } from '@/api';

export const useVehiclesStore = defineStore('vehicles', {
    state: () => ({
        items: [],
        meta: null,
        loading: false,
        error: null,
        filters: { q: '', unit_type_id: null, ownership_type_id: null, page: 1, per_page: 25 },
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
                if (this.filters.unit_type_id) params.unit_type_id = this.filters.unit_type_id;
                if (this.filters.ownership_type_id) params.ownership_type_id = this.filters.ownership_type_id;

                const body = await vehiclesApi.list(params);
                this.items = body.data ?? [];
                this.meta = body.meta ?? null;
            } catch (e) {
                this.error = errorMessage(e, 'Failed to load vehicles');
            } finally {
                this.loading = false;
            }
        },
    },
});
