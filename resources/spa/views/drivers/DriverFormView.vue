<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { useReferencesStore } from '@/stores/references';
import { driversApi, unwrap, fieldErrors, errorMessage } from '@/api';

const props = defineProps({ id: { type: [String, Number], default: null } });
const router = useRouter();
const toast = useToast();
const references = useReferencesStore();

const isEdit = computed(() => props.id != null);
const loading = ref(false);
const submitting = ref(false);
const errors = ref({});

const form = reactive({
    firstname: '', middlename: '', lastname: '', phone: '', email: '',
    password: '', dob: null, ssn: '', hire_date: null, driver_type_id: null,
});

const driverTypeOptions = computed(() => references.options('driver_types'));

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
            const d = unwrap(await driversApi.get(props.id));
            Object.assign(form, {
                firstname: d.firstname ?? '', lastname: d.lastname ?? '', phone: d.phone ?? '',
                email: d.email ?? '', ssn: d.ssn ?? '', driver_type_id: d.driver_type_id ?? null,
                dob: toDate(d.dob), hire_date: toDate(d.hire_date),
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
        firstname: form.firstname, middlename: form.middlename || null, lastname: form.lastname,
        phone: form.phone, email: form.email, ssn: form.ssn || null,
        dob: toYmd(form.dob), hire_date: toYmd(form.hire_date),
        driver_type_id: form.driver_type_id,
    };
    if (!isEdit.value && form.password) payload.password = form.password;

    try {
        if (isEdit.value) {
            await driversApi.update(props.id, payload);
            toast.add({ severity: 'success', summary: 'Saved', detail: 'Driver updated.', life: 3000 });
        } else {
            await driversApi.create(payload);
            toast.add({ severity: 'success', summary: 'Created', detail: 'Driver created.', life: 3000 });
        }
        router.push({ name: 'drivers' });
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = fieldErrors(e);
        } else {
            toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
        }
    } finally {
        submitting.value = false;
    }
}

const err = (f) => errors.value[f]?.[0];
</script>

<template>
    <div class="surface-card surface-card-pad form-wrap">
        <Message v-if="loading" severity="secondary" :closable="false">Loading driver…</Message>

        <form v-else @submit.prevent="submit">
            <div class="form-grid">
                <div class="form-field">
                    <label>First name *</label>
                    <InputText v-model="form.firstname" :invalid="!!err('firstname')" />
                    <small v-if="err('firstname')" class="field-error">{{ err('firstname') }}</small>
                </div>
                <div class="form-field">
                    <label>Last name *</label>
                    <InputText v-model="form.lastname" :invalid="!!err('lastname')" />
                    <small v-if="err('lastname')" class="field-error">{{ err('lastname') }}</small>
                </div>
                <div class="form-field">
                    <label>Middle name</label>
                    <InputText v-model="form.middlename" />
                </div>
                <div class="form-field">
                    <label>Phone <span v-if="!isEdit">*</span></label>
                    <InputText v-model="form.phone" :invalid="!!err('phone')" />
                    <small v-if="err('phone')" class="field-error">{{ err('phone') }}</small>
                </div>
                <div class="form-field">
                    <label>Email *</label>
                    <InputText v-model="form.email" type="email" :invalid="!!err('email')" />
                    <small v-if="err('email')" class="field-error">{{ err('email') }}</small>
                </div>
                <div v-if="!isEdit" class="form-field">
                    <label>Password</label>
                    <Password v-model="form.password" :feedback="false" toggleMask :inputProps="{ autocomplete: 'new-password' }" />
                    <small class="text-muted">Leave blank to auto-generate.</small>
                </div>
                <div class="form-field">
                    <label>Driver type</label>
                    <Select v-model="form.driver_type_id" :options="driverTypeOptions" optionLabel="label" optionValue="value" showClear placeholder="Select" />
                </div>
                <div class="form-field">
                    <label>SSN</label>
                    <InputText v-model="form.ssn" :invalid="!!err('ssn')" />
                    <small v-if="err('ssn')" class="field-error">{{ err('ssn') }}</small>
                </div>
                <div class="form-field">
                    <label>Date of birth</label>
                    <DatePicker v-model="form.dob" dateFormat="yy-mm-dd" showIcon />
                </div>
                <div class="form-field">
                    <label>Hire date</label>
                    <DatePicker v-model="form.hire_date" dateFormat="yy-mm-dd" showIcon />
                </div>
            </div>

            <div class="form-actions">
                <Button label="Cancel" severity="secondary" text @click="router.push({ name: 'drivers' })" />
                <Button type="submit" :label="isEdit ? 'Save changes' : 'Create driver'" icon="pi pi-check" :loading="submitting" />
            </div>
        </form>
    </div>
</template>

<style scoped>
.form-wrap { max-width: 900px; }
</style>
