import { api } from './client';

/**
 * Standard SPA-shaped CRUD wrapper for a `/api/v1/{base}` section.
 *
 * - list(params)  → GET    {base}          (returns the full body: { data, meta, links })
 * - get(id)       → GET    {base}/{id}      (returns the full body: { data })
 * - create(body)  → POST   {base}
 * - update(id,b)  → PUT    {base}/{id}
 * - remove(id)    → DELETE  {base}/{id}
 *
 * Spread the result and add section-specific endpoints as needed.
 */
export function crud(base) {
    return {
        list: (params = {}) => api.get(base, { params }).then((r) => r.data),
        get: (id) => api.get(`${base}/${id}`).then((r) => r.data),
        create: (payload) => api.post(base, payload).then((r) => r.data),
        update: (id, payload) => api.put(`${base}/${id}`, payload).then((r) => r.data),
        remove: (id) => api.delete(`${base}/${id}`).then((r) => r.data),
    };
}
