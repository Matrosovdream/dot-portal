<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { serviceRequestsApi, fieldErrors, errorMessage } from '@/api';

const router = useRouter();
const toast = useToast();

const form = reactive({ service_id: null, price: null, discount_price: null });
const errors = ref({});
const submitting = ref(false);

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        await serviceRequestsApi.create({
            service_id: form.service_id,
            price: form.price ?? undefined,
            discount_price: form.discount_price ?? undefined,
        });
        toast.add({ severity: 'success', summary: 'Submitted', detail: 'Service request created.', life: 3000 });
        router.push({ name: 'service-requests' });
    } catch (e) {
        if (e.response?.status === 422) errors.value = fieldErrors(e);
        else toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
    } finally {
        submitting.value = false;
    }
}

const err = (f) => errors.value[f]?.[0];
</script>

<template>
    <div class="surface-card surface-card-pad form-wrap">
        <Message severity="secondary" :closable="false" class="hint">
            Enter the service ID you want to request. Price defaults to the service's list price when left blank.
        </Message>

        <form class="form-grid" @submit.prevent="submit">
            <div class="form-field">
                <label>Service ID *</label>
                <InputNumber v-model="form.service_id" :useGrouping="false" :min="1" showButtons :invalid="!!err('service_id')" />
                <small v-if="err('service_id')" class="field-error">{{ err('service_id') }}</small>
            </div>
            <div class="form-field">
                <label>Price (optional)</label>
                <InputNumber v-model="form.price" mode="currency" currency="USD" :min="0" />
                <small v-if="err('price')" class="field-error">{{ err('price') }}</small>
            </div>
            <div class="form-field">
                <label>Discount price (optional)</label>
                <InputNumber v-model="form.discount_price" mode="currency" currency="USD" :min="0" />
                <small v-if="err('discount_price')" class="field-error">{{ err('discount_price') }}</small>
            </div>
            <div class="form-field col-span-2 form-actions">
                <Button label="Cancel" severity="secondary" text @click="router.push({ name: 'service-requests' })" />
                <Button type="submit" label="Submit request" icon="pi pi-check" :loading="submitting" />
            </div>
        </form>
    </div>
</template>

<style scoped>
.form-wrap { max-width: 760px; }
.hint { margin-bottom: 1.25rem; }
</style>
