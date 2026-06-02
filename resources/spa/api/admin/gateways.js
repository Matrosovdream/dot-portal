import { api } from '../client';

export const adminGatewaysApi = {
    list: (params = {}) => api.get('/admin/gateways', { params }).then((r) => r.data),
};
