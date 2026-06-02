<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import Card from 'primevue/card';
import Button from 'primevue/button';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const auth = useAuthStore();

const firstName = computed(() => auth.me?.firstname || '');

async function signOut() {
    await auth.logout();
    router.replace({ name: 'login' });
}
</script>

<template>
    <div class="auth-page">
        <Card class="auth-card">
            <template #title>
                <div class="auth-head">
                    <div class="auth-brand">DOT Portal</div>
                    <div class="auth-sub">Account created</div>
                </div>
            </template>
            <template #content>
                <div class="pending-body">
                    <i class="pi pi-clock pending-icon" />
                    <p>
                        Thanks{{ firstName ? `, ${firstName}` : '' }} — your account has been created and is
                        <strong>pending activation</strong>. You'll get full access to the dashboard once your
                        registration is complete and approved.
                    </p>
                    <p class="text-muted">If you were asked to verify your email, check your inbox.</p>
                </div>

                <div class="auth-form">
                    <Button label="Verify email" icon="pi pi-envelope" outlined @click="router.push({ name: 'verify-email' })" />
                    <Button label="Sign out" icon="pi pi-sign-out" severity="secondary" text @click="signOut" />
                </div>
            </template>
        </Card>
    </div>
</template>

<style scoped>
.pending-body { text-align: center; margin-bottom: 1rem; }
.pending-icon { font-size: 2.25rem; color: var(--p-primary-color); display: block; margin-bottom: 0.75rem; }
.pending-body p { margin: 0 0 0.5rem; }
</style>
