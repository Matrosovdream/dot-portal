import axios from 'axios';

export const api = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
    withXSRFToken: true,
    headers: { Accept: 'application/json' },
});

let csrfBootstrapped = false;

export async function bootstrapCsrf() {
    if (csrfBootstrapped) return;
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
    csrfBootstrapped = true;
}

api.interceptors.response.use(
    (r) => r,
    (err) => {
        const status = err.response?.status;
        if (status === 401) {
            window.dispatchEvent(new CustomEvent('auth:expired'));
        }
        if (status === 419) {
            // CSRF token expired — reset bootstrap flag so next request re-fetches
            csrfBootstrapped = false;
        }
        return Promise.reject(err);
    },
);

/**
 * Single-resource endpoints return `{ data: {...} }`; aggregate/bundle
 * endpoints sometimes return the object directly. `unwrap` normalises both.
 */
export function unwrap(body) {
    return body?.data ?? body;
}

/** Pull a Laravel validation-error map (`{ field: [msg] }`) out of an axios error. */
export function fieldErrors(err) {
    return err?.response?.data?.errors ?? {};
}

/** Best-effort human message from an axios error. */
export function errorMessage(err, fallback = 'Something went wrong') {
    return err?.response?.data?.message ?? err?.message ?? fallback;
}
