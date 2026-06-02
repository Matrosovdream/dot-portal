import { api, bootstrapCsrf } from './client';

export const authApi = {
    async login(payload) {
        await bootstrapCsrf();
        return (await api.post('/auth/login', payload)).data;
    },
    logout: () => api.post('/auth/logout').then((r) => r.data),
    me: () => api.get('/auth/me').then((r) => r.data),
    async register(payload) {
        await bootstrapCsrf();
        return (await api.post('/auth/register', payload)).data;
    },
    passwordEmail: (payload) => api.post('/auth/password/email', payload).then((r) => r.data),
    passwordReset: (payload) => api.post('/auth/password/reset', payload).then((r) => r.data),
    passwordUpdate: (payload) => api.put('/auth/password', payload).then((r) => r.data),
    emailVerifyStatus: () => api.get('/auth/email/verify').then((r) => r.data),
    emailVerifyResend: () => api.post('/auth/email/verify/resend').then((r) => r.data),
    loginOnetime: (token) => api.get(`/auth/login-onetime/${token}`).then((r) => r.data),
};
