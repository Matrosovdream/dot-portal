import { api } from './client';

export const globalsApi = {
    get: () => api.get('/globals').then((r) => r.data),
};
