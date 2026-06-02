import { api } from '../client';

export const adminRequestsApi = {
    list: (params = {}) => api.get('/admin/requests', { params }).then((r) => r.data),
    get: (id) => api.get(`/admin/requests/${id}`).then((r) => r.data),
    update: (id, payload) => api.put(`/admin/requests/${id}`, payload).then((r) => r.data),
    updateStatus: (id, payload) => api.post(`/admin/requests/${id}/status`, payload).then((r) => r.data),
    remove: (id) => api.delete(`/admin/requests/${id}`).then((r) => r.data),
};
