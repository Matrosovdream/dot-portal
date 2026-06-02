<script setup>
import { useRouter } from 'vue-router';
import Button from 'primevue/button';
const router = useRouter();
</script>

<template>
    <div class="err-page">
        <div class="err-code">403</div>
        <h2>Access denied</h2>
        <p class="text-muted">You don't have permission to view this page.</p>
        <Button label="Back to dashboard" icon="pi pi-home" @click="router.push('/dashboard')" />
    </div>
</template>

<style scoped>
.err-page { text-align: center; padding: 4rem 1rem; }
.err-code { font-size: 5rem; font-weight: 800; color: var(--p-primary-color); line-height: 1; }
.err-page h2 { margin: 0.5rem 0 0.25rem; }
.err-page p { margin-bottom: 1.5rem; }
</style>
