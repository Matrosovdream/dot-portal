<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { adminSettingsApi, unwrap, fieldErrors, errorMessage } from '@/api';

const toast = useToast();

const FIELDS = [
    { name: 'sitename', label: 'Site name', type: 'text' },
    { name: 'email', label: 'Email', type: 'text' },
    { name: 'phone', label: 'Phone', type: 'text' },
    { name: 'address', label: 'Address', type: 'text' },
    { name: 'work_time', label: 'Work time', type: 'textarea' },
    { name: 'copyright_text', label: 'Copyright text', type: 'textarea' },
];

const form = reactive({});
const errors = ref({});
const loading = ref(true);
const submitting = ref(false);

onMounted(async () => {
    try {
        const data = unwrap(await adminSettingsApi.get()) ?? {};
        FIELDS.forEach((f) => (form[f.name] = data[f.name] ?? ''));
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Load failed', detail: errorMessage(e), life: 4000 });
    } finally {
        loading.value = false;
    }
});

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        await adminSettingsApi.update({ ...form });
        toast.add({ severity: 'success', summary: 'Saved', detail: 'Settings updated.', life: 3000 });
    } catch (e) {
        if (e.response?.status === 422) errors.value = fieldErrors(e);
        else toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
    } finally {
        submitting.value = false;
    }
}

const err = (n) => errors.value[n]?.[0];
</script>

<template>
    <div class="surface-card surface-card-pad form-wrap">
        <Message v-if="loading" severity="secondary" :closable="false">Loading…</Message>
        <form v-else @submit.prevent="submit">
            <div class="form-grid">
                <div
                    v-for="f in FIELDS"
                    :key="f.name"
                    class="form-field"
                    :class="{ 'col-span-2': f.type === 'textarea' }"
                >
                    <label>{{ f.label }}</label>
                    <Textarea v-if="f.type === 'textarea'" v-model="form[f.name]" rows="3" autoResize :invalid="!!err(f.name)" />
                    <InputText v-else v-model="form[f.name]" :invalid="!!err(f.name)" />
                    <small v-if="err(f.name)" class="field-error">{{ err(f.name) }}</small>
                </div>
            </div>
            <div class="form-actions">
                <Button type="submit" label="Save settings" icon="pi pi-check" :loading="submitting" />
            </div>
        </form>
    </div>
</template>

<style scoped>
.form-wrap { max-width: 760px; }
</style>
