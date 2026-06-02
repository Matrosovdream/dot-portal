import { api } from '../client';
import { crud } from '../resource';

export const adminSubManagerApi = {
    ...crud('/admin/user-subscriptions'),
    sendOnetime: (id) => api.post(`/admin/user-subscriptions/${id}/send-onetime`).then((r) => r.data),
    sendPaymentLink: (id) =>
        api.post(`/admin/user-subscriptions/${id}/send-payment-link`).then((r) => r.data),
};
