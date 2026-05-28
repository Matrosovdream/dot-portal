<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';

const router = useRouter();
const auth = useAuthStore();

const roleBadge = computed(() => {
    if (auth.isAdmin)   return 'admin';
    if (auth.isManager) return 'manager';
    if (auth.isCompany) return 'company';
    if (auth.isDriver)  return 'driver';
    return 'user';
});

async function logout() {
    await auth.logout();
    router.replace({ name: 'login' });
}
</script>

<template>
    <div class="app-layout">
        <header class="topbar">
            <div class="brand">
                <span class="brand-mark">DOT</span>
                <span class="brand-name">Portal</span>
            </div>

            <div class="topbar-spacer" />

            <div class="user-block" v-if="auth.me">
                <Avatar
                    :label="(auth.me.firstname || auth.me.email).charAt(0).toUpperCase()"
                    shape="circle"
                    style="background:#6366f1;color:#fff"
                />
                <div class="user-meta">
                    <div class="user-name">{{ auth.me.fullname || auth.me.email }}</div>
                    <div class="user-role">{{ roleBadge }}</div>
                </div>
                <Button
                    label="Sign out"
                    icon="pi pi-sign-out"
                    severity="secondary"
                    text
                    @click="logout"
                />
            </div>
        </header>

        <main class="app-main">
            <router-view />
        </main>
    </div>
</template>

<style scoped>
.app-layout {
    min-height: 100vh;
    background: #f8fafc;
}
.topbar {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.85rem 1.5rem;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}
.brand { display: flex; align-items: baseline; gap: 0.5rem; }
.brand-mark { font-weight: 800; color: #6366f1; letter-spacing: 0.05em; }
.brand-name { font-weight: 600; color: #0f172a; }
.topbar-spacer { flex: 1; }
.user-block { display: flex; align-items: center; gap: 0.75rem; }
.user-meta { line-height: 1.2; text-align: right; }
.user-name { font-weight: 600; color: #0f172a; font-size: 0.9rem; }
.user-role { font-size: 0.75rem; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; }
.app-main { padding: 1.5rem; }
</style>
