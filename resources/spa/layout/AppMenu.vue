<script setup>
import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useAuthStore } from '@/stores/auth';
import AppMenuItem from '@/layout/AppMenuItem.vue';

const auth = useAuthStore();
const { me } = storeToRefs(auth);

/** True when the user holds any of the given roles (empty/undefined = everyone). */
function can(roles) {
    if (!roles || roles.length === 0) return true;
    const flags = me.value?.flags ?? {};
    return roles.some((r) => flags[`is_${r}`]);
}

/** Build a leaf item, hidden (visible:false) when the role check fails. */
function item(label, icon, to, roles) {
    return { label, icon, to, visible: can(roles) };
}

const model = computed(() => {
    const groups = [
        {
            label: 'Dashboard',
            items: [item('Home', 'pi pi-fw pi-home', '/dashboard')],
        },
        {
            label: 'Operations',
            items: [
                item('Drivers', 'pi pi-fw pi-id-card', '/drivers', ['driver', 'company', 'admin', 'manager']),
                item('Vehicles', 'pi pi-fw pi-truck', '/vehicles', ['driver', 'company', 'admin', 'manager']),
                item('Insurance', 'pi pi-fw pi-shield', '/insurance-vehicles'),
                item('Service Requests', 'pi pi-fw pi-file-edit', '/service-requests', ['driver', 'company']),
                item('Clearing House', 'pi pi-fw pi-building-columns', '/clearing-house'),
                item('To-Do', 'pi pi-fw pi-check-square', '/todo'),
                item('Documents', 'pi pi-fw pi-folder', '/documents'),
                item('Saferweb', 'pi pi-fw pi-search-plus', '/saferweb', ['driver', 'company']),
            ],
        },
        {
            label: 'Billing',
            items: [
                item('Subscription', 'pi pi-fw pi-star', '/subscription', ['driver', 'company']),
                item('Orders', 'pi pi-fw pi-shopping-cart', '/orders', ['admin', 'manager']),
            ],
        },
        {
            label: 'Administration',
            roles: ['admin', 'manager'],
            items: [
                item('Requests Manage', 'pi pi-fw pi-inbox', '/admin/requests'),
                item('Services', 'pi pi-fw pi-cog', '/admin/services'),
                item('Service Fields', 'pi pi-fw pi-list', '/admin/service-fields'),
                item('Service Groups', 'pi pi-fw pi-sitemap', '/admin/service-groups'),
                item('Sub Plans', 'pi pi-fw pi-tags', '/admin/sub-plans'),
                item('Sub Requests', 'pi pi-fw pi-envelope', '/admin/sub-requests'),
                item('Plan Fees', 'pi pi-fw pi-dollar', '/admin/plan-fees'),
                item('User Subscriptions', 'pi pi-fw pi-users', '/admin/user-subscriptions'),
                item('Notifications', 'pi pi-fw pi-bell', '/admin/notifications-manage', ['admin']),
            ],
        },
        {
            label: 'System',
            roles: ['admin'],
            items: [
                item('Users', 'pi pi-fw pi-user', '/admin/users'),
                item('Settings', 'pi pi-fw pi-sliders-h', '/admin/settings'),
                item('Gateways', 'pi pi-fw pi-credit-card', '/admin/gateways'),
            ],
        },
    ];

    // Drop groups the user can't see, or whose every child is hidden by role.
    return groups
        .filter((g) => can(g.roles))
        .filter((g) => g.items.some((it) => it.visible !== false));
});
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(group, i) in model" :key="group.label + i">
            <app-menu-item :item="group" :index="i" :root="true" />
        </template>
    </ul>
</template>
