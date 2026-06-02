import { api } from './client';
import { crud } from './resource';

export const driversApi = {
    ...crud('/drivers'),
    terminate: (id) => api.post(`/drivers/${id}/terminate`).then((r) => r.data),
    sendOnetime: (id) => api.post(`/drivers/${id}/send-onetime`).then((r) => r.data),
};
