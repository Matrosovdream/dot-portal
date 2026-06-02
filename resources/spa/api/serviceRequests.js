import { api } from './client';

export const serviceRequestsApi = {
    list: (params = {}) => api.get('/service-requests', { params }).then((r) => r.data),
    get: (id) => api.get(`/service-requests/${id}`).then((r) => r.data),
    create: (payload) => api.post('/service-requests', payload).then((r) => r.data),
};
