import { api } from './client';

export const documentsApi = {
    list: (params = {}) => api.get('/documents', { params }).then((r) => r.data),
};
