import { api } from './client';

export const ordersApi = {
    list: (params = {}) => api.get('/orders', { params }).then((r) => r.data),
    get: (id) => api.get(`/orders/${id}`).then((r) => r.data),
    payForm: (id) => api.get(`/orders/${id}/pay`).then((r) => r.data),
    pay: (id, payload) => api.post(`/orders/${id}/pay`, payload).then((r) => r.data),
    payments: (id) => api.get(`/orders/${id}/payments`).then((r) => r.data),
};
