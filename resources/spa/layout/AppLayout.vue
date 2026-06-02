<script setup>
import { computed, watch, ref, onBeforeUnmount, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
import AppTopbar from '@/layout/AppTopbar.vue';
import AppSidebar from '@/layout/AppSidebar.vue';
import AppBreadcrumb from '@/layout/AppBreadcrumb.vue';
import AppFooter from '@/layout/AppFooter.vue';
import { useLayout } from '@/layout/composables/layout';
import { useReferencesStore } from '@/stores/references';
import { useNotificationsStore } from '@/stores/notifications';

const { layoutConfig, layoutState, isSidebarActive, closeMobileMenu } = useLayout();
const route = useRoute();

const outsideClickListener = ref(null);

const containerClass = computed(() => ({
    'layout-overlay': layoutConfig.menuMode === 'overlay',
    'layout-static': layoutConfig.menuMode === 'static',
    'layout-static-inactive': layoutState.staticMenuDesktopInactive && layoutConfig.menuMode === 'static',
    'layout-overlay-active': layoutState.overlayMenuActive,
    'layout-mobile-active': layoutState.staticMenuMobileActive,
}));

watch(isSidebarActive, (active) => {
    if (active) bindOutsideClickListener();
    else unbindOutsideClickListener();
});

// Close the mobile drawer whenever the route changes.
watch(() => route.path, () => closeMobileMenu());

function bindOutsideClickListener() {
    if (outsideClickListener.value) return;
    outsideClickListener.value = (event) => {
        if (isOutsideClicked(event)) closeMobileMenu();
    };
    document.addEventListener('click', outsideClickListener.value);
}

function unbindOutsideClickListener() {
    if (!outsideClickListener.value) return;
    document.removeEventListener('click', outsideClickListener.value);
    outsideClickListener.value = null;
}

function isOutsideClicked(event) {
    const sidebarEl = document.querySelector('.layout-sidebar');
    const topbarButton = document.querySelector('.layout-menu-button');
    return !(
        sidebarEl?.isSameNode(event.target) ||
        sidebarEl?.contains(event.target) ||
        topbarButton?.isSameNode(event.target) ||
        topbarButton?.contains(event.target)
    );
}

onMounted(() => {
    // Warm up shared data the shell + many views depend on.
    useReferencesStore().load();
    useNotificationsStore().refresh();
});

onBeforeUnmount(() => unbindOutsideClickListener());
</script>

<template>
    <div class="layout-wrapper" :class="containerClass">
        <app-topbar />
        <app-sidebar />

        <div class="layout-main-container">
            <div class="layout-main">
                <app-breadcrumb />
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </div>
            <app-footer />
        </div>

        <div class="layout-mask" @click="closeMobileMenu" />
        <Toast position="top-right" />
        <ConfirmDialog />
    </div>
</template>
