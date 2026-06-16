import { defineStore } from 'pinia';
import { profileApi, unwrap, errorMessage } from '@/api';

export const useProfileStore = defineStore('profile', {
    state: () => ({
        profile: null,
        driver: null,
        loading: false,
        error: null,
    }),
    actions: {
        async loadDriver() {
            this.driver = unwrap(await profileApi.getDriver());
            return this.driver;
        },
        async saveDriver(payload) {
            this.driver = unwrap(await profileApi.updateDriver(payload));
            return this.driver;
        },
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
