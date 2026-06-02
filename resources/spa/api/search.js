import { api } from './client';

export const searchApi = {
    global: (q) => api.get('/search/global', { params: { q } }).then((r) => r.data),
};
