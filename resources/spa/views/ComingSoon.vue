<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Button from 'primevue/button';

const route = useRoute();
const router = useRouter();
const title = computed(() => route.meta?.title || 'Section');
// Optional per-route override (e.g. for sections whose backend API isn't built).
const note = computed(() => route.meta?.note || null);
</script>

<template>
    <div class="surface-card surface-card-pad coming-soon">
        <div class="cs-icon"><i class="pi pi-wrench" /></div>
        <h2>{{ title }}</h2>
        <p v-if="note" class="text-muted">{{ note }}</p>
        <p v-else class="text-muted">
            This section's screens are on the way. Its API endpoints are already built
            and reachable under <code>/api/v1</code> — wiring up the UI is the next step.
        </p>
        <Button label="Back to dashboard" icon="pi pi-arrow-left" outlined @click="router.push('/dashboard')" />
    </div>
</template>

<style scoped>
.coming-soon {
    text-align: center;
    max-width: 560px;
    margin: 2rem auto;
}
.cs-icon {
    font-size: 2.5rem;
    color: var(--p-primary-color);
    margin-bottom: 0.5rem;
}
.coming-soon h2 { margin: 0.25rem 0; }
.coming-soon p { margin-bottom: 1.25rem; }
code {
    background: var(--p-surface-100);
    padding: 0 0.35rem;
    border-radius: 4px;
}
</style>
