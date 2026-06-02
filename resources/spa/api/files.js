import { api } from './client';

export const filesApi = {
    upload: (file, extra = {}) => {
        const form = new FormData();
        form.append('file', file);
        Object.entries(extra).forEach(([k, v]) => form.append(k, v));
        return api
            .post('/files', form, { headers: { 'Content-Type': 'multipart/form-data' } })
            .then((r) => r.data);
    },
    get: (id) => api.get(`/files/${id}`).then((r) => r.data),
    // Absolute URL for an <a href> / window.open download.
    downloadUrl: (id) => `/api/v1/files/${id}/download`,
    download: (id) =>
        api.get(`/files/${id}/download`, { responseType: 'blob' }).then((r) => r.data),
};
