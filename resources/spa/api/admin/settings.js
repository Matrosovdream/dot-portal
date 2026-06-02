import { api } from '../client';

export const adminSettingsApi = {
    get: () => api.get('/admin/settings').then((r) => r.data),
    update: (payload) => api.put('/admin/settings', payload).then((r) => r.data),
};
