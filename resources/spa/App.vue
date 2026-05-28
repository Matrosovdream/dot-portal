<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import Toast from 'primevue/toast';

const auth = useAuthStore();
onMounted(() => auth.bootstrap());
</script>

<template>
    <div class="app-shell">
        <router-view />
        <Toast />
    </div>
</template>

<style>
:root {
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
}
body, html, #app {
    margin: 0;
    padding: 0;
    min-height: 100vh;
}
.app-shell {
    min-height: 100vh;
    background: #fafafa;
}
</style>
