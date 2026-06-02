<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import InputNumber from 'primevue/inputnumber';
import Password from 'primevue/password';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import ToggleSwitch from 'primevue/toggleswitch';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { useReferencesStore } from '@/stores/references';
import { unwrap, fieldErrors, errorMessage } from '@/api';
import { sections } from './configs';
import { getByPath, resolveOptions, toDate, toYmd } from './helpers';

const props = defineProps({ id: { type: [String, Number], default: null } });
const route = useRoute();
const router = useRouter();
const toast = useToast();
const references = useReferencesStore();

const cfg = computed(() => sections[route.meta.section]);
const isEdit = computed(() => props.id != null);

// Create-only fields (identity ids the update endpoint won't accept) are hidden on edit.
const fields = computed(() => cfg.value.formFields.filter((f) => !(isEdit.value && f.createOnly)));

const form = reactive({});
const errors = ref({});
const loading = ref(false);
const submitting = ref(false);

function blankFor(field) {
    if (field.type === 'switch') return false;
    if (field.type === 'number' || field.type === 'money') return null;
    return '';
}

onMounted(async () => {
    references.load();
    fields.value.forEach((f) => {
        form[f.name] = blankFor(f);
    });

    if (isEdit.value) {
        loading.value = true;
        try {
            const data = unwrap(await cfg.value.api.get(props.id));
            fields.value.forEach((f) => {
                if (f.type === 'password') return; // never prefill passwords
                // valuePath lets a field read a nested value (e.g. role.slug) while
                // still submitting under its own flat name.
                const v = f.valuePath ? getByPath(data, f.valuePath) : data[f.name];
                if (v === undefined) return;
                form[f.name] = f.type === 'date' ? toDate(v) : v;
            });
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Load failed', detail: errorMessage(e), life: 4000 });
        } finally {
            loading.value = false;
        }
    }
});

function buildPayload() {
    const payload = {};
    fields.value.forEach((f) => {
        const v = form[f.name];
        if (f.type === 'password') {
            if (v) payload[f.name] = v; // omit when blank (keep current on edit)
            return;
        }
        payload[f.name] = f.type === 'date' ? toYmd(v) : v === '' ? null : v;
    });
    return payload;
}

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        if (isEdit.value) {
            await cfg.value.api.update(props.id, buildPayload());
            toast.add({ severity: 'success', summary: 'Saved', life: 3000 });
        } else {
            await cfg.value.api.create(buildPayload());
            toast.add({ severity: 'success', summary: 'Created', life: 3000 });
        }
        router.push(cfg.value.routeBase);
    } catch (e) {
        if (e.response?.status === 422) errors.value = fieldErrors(e);
        else toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
    } finally {
        submitting.value = false;
    }
}

const err = (name) => errors.value[name]?.[0];
const optionsFor = (f) => resolveOptions(f.options);
</script>

<template>
    <div class="surface-card surface-card-pad form-wrap">
        <Message v-if="loading" severity="secondary" :closable="false">Loading…</Message>

        <form v-else @submit.prevent="submit">
            <div class="form-grid">
                <div
                    v-for="f in fields"
                    :key="f.name"
                    class="form-field"
                    :class="{ 'col-span-2': f.type === 'textarea' }"
                >
                    <label>{{ f.label }}<span v-if="f.required"> *</span></label>

                    <Textarea v-if="f.type === 'textarea'" v-model="form[f.name]" rows="3" autoResize :invalid="!!err(f.name)" />
                    <Password
                        v-else-if="f.type === 'password'"
                        v-model="form[f.name]"
                        :feedback="false"
                        toggleMask
                        :invalid="!!err(f.name)"
                        :inputProps="{ autocomplete: 'new-password' }"
                    />
                    <InputNumber
                        v-else-if="f.type === 'money'"
                        v-model="form[f.name]"
                        mode="currency"
                        currency="USD"
                        :min="0"
                        :invalid="!!err(f.name)"
                    />
                    <InputNumber
                        v-else-if="f.type === 'number'"
                        v-model="form[f.name]"
                        :useGrouping="false"
                        :invalid="!!err(f.name)"
                    />
                    <DatePicker v-else-if="f.type === 'date'" v-model="form[f.name]" dateFormat="yy-mm-dd" showIcon />
                    <ToggleSwitch v-else-if="f.type === 'switch'" v-model="form[f.name]" />
                    <Select
                        v-else-if="f.type === 'select' && optionsFor(f)"
                        v-model="form[f.name]"
                        :options="optionsFor(f)"
                        optionLabel="label"
                        optionValue="value"
                        showClear
                        :filter="optionsFor(f).length > 8"
                        placeholder="Select"
                        :invalid="!!err(f.name)"
                    />
                    <InputText v-else v-model="form[f.name]" :type="f.type === 'email' ? 'email' : 'text'" :invalid="!!err(f.name)" />

                    <small v-if="err(f.name)" class="field-error">{{ err(f.name) }}</small>
                    <small v-else-if="f.type === 'password' && isEdit" class="text-muted">Leave blank to keep current.</small>
                </div>
            </div>

            <div class="form-actions">
                <Button label="Cancel" severity="secondary" text @click="router.push(cfg.routeBase)" />
                <Button type="submit" :label="isEdit ? 'Save changes' : `Create ${cfg.singular}`" icon="pi pi-check" :loading="submitting" />
            </div>
        </form>
    </div>
</template>

<style scoped>
.form-wrap { max-width: 920px; }
</style>
