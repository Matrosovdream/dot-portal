import { api } from './client';

export const profileApi = {
    get: () => api.get('/profile').then((r) => r.data),
    update: (payload) => api.put('/profile', payload).then((r) => r.data),
    updatePassword: (payload) => api.put('/profile/password', payload).then((r) => r.data),
    updateAddress: (payload) => api.put('/profile/address', payload).then((r) => r.data),
    getCompany: () => api.get('/profile/company').then((r) => r.data),
    updateCompany: (payload) => api.put('/profile/company', payload).then((r) => r.data),
    getDriver: () => api.get('/profile/driver').then((r) => r.data),
    updateDriver: (payload) => api.put('/profile/driver', payload).then((r) => r.data),
};
