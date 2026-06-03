<script setup>
import { onBeforeMount, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useLayout } from '@/layout/composables/layout';

const props = defineProps({
    item: { type: Object, required: true },
    index: { type: Number, default: 0 },
    root: { type: Boolean, default: true },
    parentItemKey: { type: String, default: null },
});

const route = useRoute();
const { layoutState, setActiveMenuItem, closeMobileMenu } = useLayout();

const isActiveMenu = ref(false);
const itemKey = ref(null);

onBeforeMount(() => {
    itemKey.value = props.parentItemKey
        ? props.parentItemKey + '-' + props.index
        : String(props.index);

    const activeItem = layoutState.activeMenuItem;
    isActiveMenu.value =
        activeItem === itemKey.value || (activeItem ? activeItem.startsWith(itemKey.value + '-') : false);
});

watch(
    () => layoutState.activeMenuItem,
    (activeItem) => {
        isActiveMenu.value =
            activeItem === itemKey.value || (activeItem ? activeItem.startsWith(itemKey.value + '-') : false);
    },
);

function itemClick(event, item) {
    if (item.disabled) {
        event.preventDefault();
        return;
    }

    if (item.command) {
        item.command({ originalEvent: event, item });
    }

    if (item.items) {
        // toggle a submenu
        const active = isActiveMenu.value;
        setActiveMenuItem(active ? props.parentItemKey : itemKey.value);
    } else {
        // leaf — close the mobile drawer on navigation
        if (window.innerWidth <= 991) closeMobileMenu();
        setActiveMenuItem(itemKey.value);
    }
}

function checkActiveRoute(item) {
    return item.to && route.path === item.to;
}
</script>

<template>
    <li :class="{ 'layout-root-menuitem': root, 'active-menuitem': isActiveMenu }">
        <div v-if="root && item.visible !== false" class="layout-menuitem-root-text">
            {{ item.label }}
        </div>

        <!-- parent with submenu / external link. Skipped for root items: those
             are section headers (rendered above) whose children are always shown,
             so a clickable root row would just duplicate the section title. -->
        <a
            v-if="!root && (!item.to || item.items) && item.visible !== false"
            :href="item.url"
            @click="itemClick($event, item)"
            :class="item.class"
            :target="item.target"
            tabindex="0"
        >
            <i :class="item.icon" class="layout-menuitem-icon" />
            <span class="layout-menuitem-text">{{ item.label }}</span>
            <i v-if="item.items" class="pi pi-fw pi-angle-down layout-submenu-toggler" />
        </a>

        <!-- leaf router link -->
        <router-link
            v-if="item.to && !item.items && item.visible !== false"
            @click="itemClick($event, item)"
            :class="[item.class, { 'active-route': checkActiveRoute(item) }]"
            tabindex="0"
            :to="item.to"
        >
            <i :class="item.icon" class="layout-menuitem-icon" />
            <span class="layout-menuitem-text">{{ item.label }}</span>
            <span v-if="item.badge" class="layout-menuitem-badge">{{ item.badge }}</span>
        </router-link>

        <Transition v-if="item.items && item.visible !== false" name="layout-submenu">
            <ul v-show="root ? true : isActiveMenu" class="layout-submenu">
                <app-menu-item
                    v-for="(child, i) in item.items"
                    :key="child.label + i"
                    :index="i"
                    :item="child"
                    :parentItemKey="itemKey"
                    :root="false"
                />
            </ul>
        </Transition>
    </li>
</template>
