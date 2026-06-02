import { defineStore } from 'pinia';
import { referencesApi, unwrap } from '@/api';

/**
 * Loads the `/references/bundle` aggregate once and caches it. Views pull
 * dropdown options from here via `options(key)` → [{ label, value }].
 */
export const useReferencesStore = defineStore('references', {
    state: () => ({
        bundle: {},
        loaded: false,
        loading: false,
    }),
    getters: {
        states: (s) => s.bundle.states ?? [],
        driverTypes: (s) => s.bundle.driver_types ?? [],
        vehicleUnitTypes: (s) => s.bundle.vehicle_unit_types ?? [],
        vehicleOwnershipTypes: (s) => s.bundle.vehicle_ownership_types ?? [],
        requestStatuses: (s) => s.bundle.request_statuses ?? [],
        orderStatuses: (s) => s.bundle.order_statuses ?? [],
        serviceGroups: (s) => s.bundle.service_groups ?? [],
    },
    actions: {
        async load(force = false) {
            if ((this.loaded || this.loading) && !force) return;
            this.loading = true;
            try {
                this.bundle = unwrap(await referencesApi.bundle()) ?? {};
                this.loaded = true;
            } catch {
                // References are non-blocking; views degrade to raw ids.
            } finally {
                this.loading = false;
            }
        },
        /** [{ label, value }] for a PrimeVue Select, from a bundle key. */
        options(key) {
            return (this.bundle[key] ?? []).map((r) => ({ label: r.name, value: r.id }));
        },
        /** Resolve a single id to its display name for a given bundle key. */
        label(key, id) {
            const hit = (this.bundle[key] ?? []).find((r) => r.id === id);
            return hit?.name ?? (id ?? '—');
        },
    },
});
