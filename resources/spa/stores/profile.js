import { defineStore } from 'pinia';
import { profileApi, unwrap, errorMessage } from '@/api';

export const useProfileStore = defineStore('profile', {
    state: () => ({
        profile: null,
        loading: false,
        error: null,
    }),
    actions: {
        async load(force = false) {
            if (this.profile && !force) return this.profile;
            this.loading = true;
            this.error = null;
            try {
                this.profile = unwrap(await profileApi.get());
                return this.profile;
            } catch (e) {
                this.error = errorMessage(e, 'Failed to load profile');
            } finally {
                this.loading = false;
            }
        },
        async save(payload) {
            this.profile = unwrap(await profileApi.update(payload));
            return this.profile;
        },
        async saveAddress(payload) {
            const address = unwrap(await profileApi.updateAddress(payload));
            if (this.profile) this.profile.address = address;
            return address;
        },
        async saveCompany(payload) {
            const company = unwrap(await profileApi.updateCompany(payload));
            if (this.profile) this.profile.company = company;
            return company;
        },
        changePassword(payload) {
            return profileApi.updatePassword(payload);
        },
    },
});
