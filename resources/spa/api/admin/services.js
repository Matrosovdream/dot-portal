import { api } from '../client';
import { crud } from '../resource';

export const adminServicesApi = {
    ...crud('/admin/services'),
    updateStatus: (id, payload) => api.post(`/admin/services/${id}/status`, payload).then((r) => r.data),
};
