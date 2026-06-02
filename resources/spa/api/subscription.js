import { api } from './client';

export const subscriptionApi = {
    get: () => api.get('/subscription').then((r) => r.data),
    update: (payload) => api.put('/subscription', payload).then((r) => r.data),
    cancel: () => api.post('/subscription/cancel').then((r) => r.data),
};
