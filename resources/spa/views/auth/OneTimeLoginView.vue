<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ProgressSpinner from 'primevue/progressspinner';
import Button from 'primevue/button';
import { useAuthStore } from '@/stores/auth';
import { authApi, unwrap } from '@/api';

const props = defineProps({ token: { type: String, default: '' } });
const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const failed = ref(false);

// The one-time-login email may carry ?redirect=<absolute or path URL>.
// Only honor same-origin targets; anything else falls back to the dashboard.
function resolveRedirect(raw) {
    if (!raw) return { name: 'dashboard' };
    try {
        const u = new URL(raw, window.location.origin);
        if (u.origin === window.location.origin) return u.pathname + u.search + u.hash;
    } catch {
        if (typeof raw === 'string' && raw.startsWith('/')) return raw;
    }
    return { name: 'dashboard' };
}

onMounted(async () => {
    const token = props.token || route.params.token;
    try {
        const me = unwrap(await authApi.loginOnetime(token));
        auth.setMe(me);
        router.replace(resolveRedirect(route.query.redirect ?? route.query.next));
    } catch {
        failed.value = true;
    }
});
</script>

<template>
    <div class="auth-page">
        <div style="text-align:center">
            <template v-if="!failed">
                <ProgressSpinner style="width:48px;height:48px" strokeWidth="4" />
                <p class="text-muted">Signing you in…</p>
            </template>
            <template v-else>
                <div class="auth-brand">DOT Portal</div>
                <p class="text-muted">This login link is invalid or has expired.</p>
                <router-link :to="{ name: 'login' }"><Button label="Go to sign in" /></router-link>
            </template>
        </div>
    </div>
</template>
