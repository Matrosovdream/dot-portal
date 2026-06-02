import { api } from './client';

export const todoApi = {
    list: (params = {}) => api.get('/todo', { params }).then((r) => r.data),
    company: (params = {}) => api.get('/todo/company', { params }).then((r) => r.data),
    vehicle: (params = {}) => api.get('/todo/vehicle', { params }).then((r) => r.data),
    driver: (params = {}) => api.get('/todo/driver', { params }).then((r) => r.data),
    get: (id) => api.get(`/todo/${id}`).then((r) => r.data),
};
