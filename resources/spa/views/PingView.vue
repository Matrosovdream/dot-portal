<script setup>
import { onMounted, ref } from 'vue';
import { api } from '@/api/client';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Tag from 'primevue/tag';

const ping = ref(null);
const error = ref(null);
const loading = ref(false);

async function callPing() {
    loading.value = true;
    error.value = null;
    try {
        const { data } = await api.get('/ping');
        ping.value = data;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
}

onMounted(callPing);
</script>

<template>
    <div class="ping-page">
        <Card>
            <template #title>SPA foundation</template>
            <template #subtitle>Step 01 — smoke screen</template>
            <template #content>
                <p>Vue 3 + Pinia + vue-router + PrimeVue (Sakai pending) is wired.</p>
                <div v-if="loading">Pinging API…</div>
                <div v-else-if="error" class="error">Error: {{ error }}</div>
                <div v-else-if="ping">
                    <Tag severity="success" value="API OK" />
                    <pre>{{ JSON.stringify(ping, null, 2) }}</pre>
                </div>
            </template>
            <template #footer>
                <Button label="Ping again" icon="pi pi-refresh" @click="callPing" :loading="loading" />
            </template>
        </Card>
    </div>
</template>

<style scoped>
.ping-page {
    padding: 2rem;
    max-width: 720px;
    margin: 0 auto;
}
.error { color: #b00; }
pre {
    background: #f4f4f4;
    padding: 1rem;
    border-radius: 6px;
    overflow: auto;
}
</style>
