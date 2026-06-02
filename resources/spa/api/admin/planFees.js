import { api } from '../client';

// Plan fees support index/show/create/update only — no DELETE (API returns 405).
export const adminPlanFeesApi = {
    list: (params = {}) => api.get('/admin/plan-fees', { params }).then((r) => r.data),
    get: (id) => api.get(`/admin/plan-fees/${id}`).then((r) => r.data),
    create: (payload) => api.post('/admin/plan-fees', payload).then((r) => r.data),
    update: (id, payload) => api.put(`/admin/plan-fees/${id}`, payload).then((r) => r.data),
};
