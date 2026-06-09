import { api } from './client';

/**
 * User lookup for the Operations owner-filter picker (admin/manager only).
 * Hits the lightweight /admin/user-options endpoint — returns
 * { data: [{ id, fullname, email }], ... }.
 */
export const usersApi = {
    search: (q = '', params = {}) =>
        api.get('/admin/user-options', { params: { q, per_page: 20, ...params } }).then((r) => r.data),
};
