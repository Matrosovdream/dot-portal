import { api } from '../client';
import { crud } from '../resource';

export const adminSubRequestsApi = {
    ...crud('/admin/sub-requests'),
    sendEmail: (id) => api.post(`/admin/sub-requests/${id}/send-email`).then((r) => r.data),
};
