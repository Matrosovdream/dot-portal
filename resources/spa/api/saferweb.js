import { api } from './client';

export const saferwebApi = {
    inspections: (params = {}) => api.get('/saferweb/inspections', { params }).then((r) => r.data),
    inspection: (id) => api.get(`/saferweb/inspections/${id}`).then((r) => r.data),
    crashes: (params = {}) => api.get('/saferweb/crashes', { params }).then((r) => r.data),
    crash: (id) => api.get(`/saferweb/crashes/${id}`).then((r) => r.data),
};
