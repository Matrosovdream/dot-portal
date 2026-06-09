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
    // Per-role visibility mirrors the legacy Metronic menus (one set per role,
    // app/References/Menu/*Menu.php): driver = To-Do/Documents/Profile only;
    // manager = Services + Requests only; company = the full fleet menu; admin
    // keeps the broad superset. Keep these `roles` in sync with the matching
    // route meta.roles in router/routes.js so a hidden item is also unreachable.
    const groups = [
        {
            label: 'Dashboard',
            items: [item('Home', 'pi pi-fw pi-home', '/dashboard')],
        },
        {
            label: 'Operations',
            items: [
                item('Drivers', 'pi pi-fw pi-id-card', '/drivers', ['company', 'admin', 'manager']),
                item('Vehicles', 'pi pi-fw pi-truck', '/vehicles', ['company', 'admin', 'manager']),
                item('Insurance', 'pi pi-fw pi-shield', '/insurance-vehicles', ['company', 'admin', 'manager']),
                item('Service Requests', 'pi pi-fw pi-file-edit', '/service-requests', ['company']),
                item('Clearing House', 'pi pi-fw pi-building-columns', '/clearing-house', ['company']),
                item('To-Do', 'pi pi-fw pi-check-square', '/todo', ['driver', 'company', 'manager']),
                item('Documents', 'pi pi-fw pi-folder', '/documents', ['driver', 'company', 'admin', 'manager']),
                item('Saferweb', 'pi pi-fw pi-search-plus', '/saferweb', ['company']),
            ],
        },
        {
            label: 'Billing',
            items: [
                item('Subscription', 'pi pi-fw pi-star', '/subscription', ['company']),
                item('Orders', 'pi pi-fw pi-shopping-cart', '/orders', ['admin']),
            ],
        },
        {
            label: 'Administration',
            roles: ['admin', 'manager'],
            items: [
                item('Requests Manage', 'pi pi-fw pi-inbox', '/admin/requests', ['admin', 'manager']),
                item('Services', 'pi pi-fw pi-cog', '/admin/services', ['admin', 'manager']),
                item('Service Fields', 'pi pi-fw pi-list', '/admin/service-fields', ['admin']),
                item('Service Groups', 'pi pi-fw pi-sitemap', '/admin/service-groups', ['admin']),
                item('Sub Plans', 'pi pi-fw pi-tags', '/admin/sub-plans', ['admin']),
                item('Sub Requests', 'pi pi-fw pi-envelope', '/admin/sub-requests', ['admin']),
                item('Plan Fees', 'pi pi-fw pi-dollar', '/admin/plan-fees', ['admin']),
                item('User Subscriptions', 'pi pi-fw pi-users', '/admin/user-subscriptions', ['admin']),
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
        {
            label: 'Account',
            items: [item('My Profile', 'pi pi-fw pi-user', '/profile', ['driver', 'company'])],
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
