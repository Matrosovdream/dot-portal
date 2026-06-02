<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const crumbs = computed(() => {
    const meta = route.meta?.breadcrumb;
    if (Array.isArray(meta) && meta.length) return meta;
    return [route.meta?.title || 'Dashboard'];
});

const title = computed(() => route.meta?.title || crumbs.value[crumbs.value.length - 1]);
</script>

<template>
    <div class="layout-breadcrumb">
        <div>
            <h1 class="page-title">{{ title }}</h1>
            <nav class="crumbs">
                <router-link to="/dashboard" class="crumb-home"><i class="pi pi-home" /></router-link>
                <template v-for="(c, i) in crumbs" :key="i">
                    <span class="crumb-sep">/</span>
                    <span class="crumb" :class="{ 'crumb-active': i === crumbs.length - 1 }">{{ c }}</span>
                </template>
            </nav>
        </div>
    </div>
</template>
