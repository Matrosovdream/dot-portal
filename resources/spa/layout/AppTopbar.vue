<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Menu from 'primevue/menu';
import Badge from 'primevue/badge';
import OverlayBadge from 'primevue/overlaybadge';
import { useLayout } from '@/layout/composables/layout';
import { useAuthStore } from '@/stores/auth';
import { useNotificationsStore } from '@/stores/notifications';

const { toggleMenu, toggleDarkMode, isDarkTheme } = useLayout();
const router = useRouter();
const auth = useAuthStore();
const notifications = useNotificationsStore();
const { me } = storeToRefs(auth);
const { unread } = storeToRefs(notifications);

const initials = computed(() => {
    const n = me.value?.fullname || me.value?.firstname || me.value?.email || '?';
    return n.trim().charAt(0).toUpperCase();
});

const roleBadge = computed(() => {
    const f = me.value?.flags ?? {};
    if (f.is_admin) return 'Admin';
    if (f.is_manager) return 'Manager';
    if (f.is_company) return 'Company';
    if (f.is_driver) return 'Driver';
    return 'User';
});

const userMenu = ref();
const userMenuItems = computed(() => [
    { label: me.value?.fullname || me.value?.email, disabled: true },
    { separator: true },
    { label: 'My Profile', icon: 'pi pi-user', command: () => router.push('/profile') },
    { label: 'Subscription', icon: 'pi pi-star', command: () => router.push('/subscription') },
    { separator: true },
    { label: 'Sign out', icon: 'pi pi-sign-out', command: logout },
]);

function toggleUserMenu(event) {
    userMenu.value.toggle(event);
}

async function logout() {
    await auth.logout();
    router.replace({ name: 'login' });
}
</script>

<template>
    <div class="layout-topbar">
        <div class="layout-topbar-start">
            <button class="layout-menu-button" @click="toggleMenu" type="button" v-tooltip.bottom="'Menu'">
                <i class="pi pi-bars" />
            </button>
            <router-link to="/dashboard" class="layout-topbar-logo">
                <span class="brand-mark">DOT</span>
                <span class="brand-name">Portal</span>
            </router-link>
        </div>

        <div class="layout-topbar-end">
            <button
                class="layout-topbar-action"
                type="button"
                @click="toggleDarkMode"
                v-tooltip.bottom="isDarkTheme ? 'Light mode' : 'Dark mode'"
            >
                <i :class="['pi', isDarkTheme ? 'pi-sun' : 'pi-moon']" />
            </button>

            <button
                class="layout-topbar-action"
                type="button"
                @click="router.push('/notifications')"
                v-tooltip.bottom="'Notifications'"
            >
                <OverlayBadge v-if="unread > 0" :value="unread > 99 ? '99+' : unread" severity="danger">
                    <i class="pi pi-bell" />
                </OverlayBadge>
                <i v-else class="pi pi-bell" />
            </button>

            <button class="layout-topbar-user" type="button" @click="toggleUserMenu" aria-haspopup="true">
                <Avatar :label="initials" shape="circle" class="topbar-avatar" />
                <span class="topbar-user-meta">
                    <span class="topbar-user-name">{{ me?.firstname || me?.email }}</span>
                    <Badge :value="roleBadge" severity="info" />
                </span>
                <i class="pi pi-angle-down" />
            </button>
            <Menu ref="userMenu" :model="userMenuItems" :popup="true" />
        </div>
    </div>
</template>
