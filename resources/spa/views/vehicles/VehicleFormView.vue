<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { useReferencesStore } from '@/stores/references';
import { vehiclesApi, unwrap, fieldErrors, errorMessage } from '@/api';

const props = defineProps({ id: { type: [String, Number], default: null } });
const router = useRouter();
const toast = useToast();
const references = useReferencesStore();

const isEdit = computed(() => props.id != null);
const loading = ref(false);
const submitting = ref(false);
const errors = ref({});

const form = reactive({
    number: '', vin: '', unit_type_id: null, ownership_type_id: null,
    reg_expire_date: null, inspection_expire_date: null, driver_id: null,
});

const unitTypeOptions = computed(() => references.options('vehicle_unit_types'));
const ownershipOptions = computed(() => references.options('vehicle_ownership_types'));

function toDate(v) { return v ? new Date(`${v}T00:00:00`) : null; }
function toYmd(v) {
    if (!v) return null;
    if (typeof v === 'string') return v;
    const d = v;
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

onMounted(async () => {
    references.load();
    if (isEdit.value) {
        loading.value = true;
        try {
            const v = unwrap(await vehiclesApi.get(props.id));
            Object.assign(form, {
                number: v.number ?? '', vin: v.vin ?? '',
                unit_type_id: v.unit_type_id ?? null, ownership_type_id: v.ownership_type_id ?? null,
                driver_id: v.driver_id ?? null,
                reg_expire_date: toDate(v.reg_expire_date), inspection_expire_date: toDate(v.inspection_expire_date),
            });
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Load failed', detail: errorMessage(e), life: 4000 });
        } finally {
            loading.value = false;
        }
    }
});

async function submit() {
    errors.value = {};
    submitting.value = true;
    const payload = {
        number: form.number, vin: form.vin || null,
        unit_type_id: form.unit_type_id, ownership_type_id: form.ownership_type_id,
        reg_expire_date: toYmd(form.reg_expire_date), inspection_expire_date: toYmd(form.inspection_expire_date),
        driver_id: form.driver_id || null,
    };
    try {
        if (isEdit.value) {
            await vehiclesApi.update(props.id, payload);
            toast.add({ severity: 'success', summary: 'Saved', detail: 'Vehicle updated.', life: 3000 });
        } else {
            await vehiclesApi.create(payload);
            toast.add({ severity: 'success', summary: 'Created', detail: 'Vehicle created.', life: 3000 });
        }
        router.push({ name: 'vehicles' });
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
        <Message v-if="loading" severity="secondary" :closable="false">Loading vehicle…</Message>

        <form v-else @submit.prevent="submit">
            <div class="form-grid">
                <div class="form-field">
                    <label>Unit number *</label>
                    <InputText v-model="form.number" :invalid="!!err('number')" />
                    <small v-if="err('number')" class="field-error">{{ err('number') }}</small>
                </div>
                <div class="form-field">
                    <label>VIN</label>
                    <InputText v-model="form.vin" :invalid="!!err('vin')" />
                    <small v-if="err('vin')" class="field-error">{{ err('vin') }}</small>
                </div>
                <div class="form-field">
                    <label>Unit type</label>
                    <Select v-model="form.unit_type_id" :options="unitTypeOptions" optionLabel="label" optionValue="value" showClear placeholder="Select" />
                </div>
                <div class="form-field">
                    <label>Ownership type</label>
                    <Select v-model="form.ownership_type_id" :options="ownershipOptions" optionLabel="label" optionValue="value" showClear placeholder="Select" />
                </div>
                <div class="form-field">
                    <label>Registration expires</label>
                    <DatePicker v-model="form.reg_expire_date" dateFormat="yy-mm-dd" showIcon />
                </div>
                <div class="form-field">
                    <label>Inspection expires</label>
                    <DatePicker v-model="form.inspection_expire_date" dateFormat="yy-mm-dd" showIcon />
                </div>
                <div class="form-field">
                    <label>Driver ID (optional)</label>
                    <InputNumber v-model="form.driver_id" :useGrouping="false" showButtons :min="1" />
                </div>
            </div>

            <div class="form-actions">
                <Button label="Cancel" severity="secondary" text @click="router.push({ name: 'vehicles' })" />
                <Button type="submit" :label="isEdit ? 'Save changes' : 'Create vehicle'" icon="pi pi-check" :loading="submitting" />
            </div>
        </form>
    </div>
</template>

<style scoped>
.form-wrap { max-width: 900px; }
</style>
