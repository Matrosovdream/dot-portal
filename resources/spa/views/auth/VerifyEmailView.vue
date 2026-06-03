<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { api, authApi, errorMessage } from '@/api';

const route = useRoute();

const loading = ref(true);
const verified = ref(false);
const email = ref('');
const statusMsg = ref('');
const errorMsg = ref('');
const resending = ref(false);

async function refreshStatus() {
    try {
        const res = await authApi.emailVerifyStatus();
        verified.value = !!res?.verified;
        email.value = res?.email || '';
    } catch (e) {
        errorMsg.value = errorMessage(e, 'Could not load verification status');
    }
}

onMounted(async () => {
    // The verification email links here with ?verify=<signed /api/v1 url>.
    // Complete verification by GETting that signed URL with the session cookie.
    const signed = route.query.verify;
    if (signed) {
        try {
            await api.get(signed);
        } catch (e) {
            errorMsg.value = errorMessage(e, 'Verification link is invalid or expired');
        }
    }
    await refreshStatus();
    loading.value = false;
});

async function resend() {
    resending.value = true;
    statusMsg.value = '';
    errorMsg.value = '';
    try {
        const res = await authApi.emailVerifyResend();
        statusMsg.value = res?.message || 'Verification link sent.';
    } catch (e) {
        errorMsg.value =
            e.response?.status === 429
                ? 'Too many attempts. Please try again shortly.'
                : errorMessage(e, 'Could not resend verification email');
    } finally {
        resending.value = false;
    }
}
</script>

<template>
    <div class="auth-head">
        <h1>Email verification</h1>
    </div>

    <div v-if="loading" class="text-muted" style="text-align:center">
        <i class="pi pi-spin pi-spinner" /> Checking…
    </div>

    <template v-else>
        <Message v-if="verified" severity="success" :closable="false">
            Your email{{ email ? ` (${email})` : '' }} is verified.
        </Message>
        <Message v-else severity="warn" :closable="false">
            Your email{{ email ? ` (${email})` : '' }} isn't verified yet. Check your inbox,
            or resend the verification link below.
        </Message>

        <Message v-if="statusMsg" severity="info" :closable="false" class="mt">{{ statusMsg }}</Message>
        <Message v-if="errorMsg" severity="error" :closable="false" class="mt">{{ errorMsg }}</Message>

        <div class="auth-form mt">
            <Button v-if="!verified" label="Resend verification email" icon="pi pi-envelope" :loading="resending" @click="resend" />
            <router-link :to="{ name: 'dashboard' }"><Button label="Go to dashboard" severity="secondary" outlined class="w-full" /></router-link>
        </div>
    </template>
</template>

<style scoped>
.mt { margin-top: 0.75rem; }
.w-full { width: 100%; }
</style>
