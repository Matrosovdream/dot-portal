import { api } from './client';

export const dashboardApi = {
    home: () => api.get('/dashboard/home').then((r) => r.data),
};
