<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Select from 'primevue/select';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import UserSelect from '@/components/UserSelect.vue';
import { useVehiclesStore } from '@/stores/vehicles';
import { useReferencesStore } from '@/stores/references';
import { useAuthStore } from '@/stores/auth';
import { vehiclesApi, errorMessage } from '@/api';

const router = useRouter();
const toast = useToast();
const confirm = useConfirm();
const store = useVehiclesStore();
const references = useReferencesStore();
const auth = useAuthStore();
const { items, meta, loading, filters } = storeToRefs(store);

const isAdminOrManager = computed(() => auth.isAdmin || auth.isManager);
const ownerUserLabel = (o) => (o ? `${o.fullname} (${o.email})` : '—');

const search = ref('');
let debounce;

onMounted(() => {
    references.load();
    store.fetch();
});

const unitTypeOptions = computed(() => [{ label: 'All unit types', value: null }, ...references.options('vehicle_unit_types')]);
const ownershipOptions = computed(() => [{ label: 'All ownership', value: null }, ...references.options('vehicle_ownership_types')]);
const first = computed(() => ((meta.value?.current_page ?? 1) - 1) * (meta.value?.per_page ?? filters.value.per_page));
const total = computed(() => meta.value?.total ?? 0);

function onSearch() {
    clearTimeout(debounce);
    debounce = setTimeout(() => store.fetch({ q: search.value, page: 1 }), 350);
}
function onPage(e) {
    store.fetch({ page: e.page + 1, per_page: e.rows });
}
const unitLabel = (id) => references.label('vehicle_unit_types', id);
const ownerLabel = (id) => references.label('vehicle_ownership_types', id);
const fmtDate = (v) => (v ? new Date(v).toLocaleDateString() : '—');

function confirmDelete(v) {
    confirm.require({
        header: 'Delete vehicle',
        message: `Delete vehicle ${v.number}? This cannot be undone.`,
        icon: 'pi pi-trash',
        acceptClass: 'p-button-danger',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await vehiclesApi.remove(v.id);
                toast.add({ severity: 'success', summary: 'Deleted', life: 3000 });
                store.fetch();
            } catch (e) {
                toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
            }
        },
    });
}
</script>

<template>
    <div>
        <div class="list-toolbar">
            <IconField>
                <InputIcon class="pi pi-search" />
                <InputText v-model="search" placeholder="Search number or VIN" @input="onSearch" />
            </IconField>
            <Select :options="unitTypeOptions" optionLabel="label" optionValue="value" :modelValue="filters.unit_type_id" @update:modelValue="(v) => store.fetch({ unit_type_id: v, page: 1 })" style="min-width: 12rem" />
            <Select :options="ownershipOptions" optionLabel="label" optionValue="value" :modelValue="filters.ownership_type_id" @update:modelValue="(v) => store.fetch({ ownership_type_id: v, page: 1 })" style="min-width: 12rem" />
            <UserSelect v-if="isAdminOrManager" :modelValue="filters.user_id" placeholder="Filter by user" @update:modelValue="(v) => store.fetch({ user_id: v, page: 1 })" />
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="store.fetch()" />
            <Button label="New Vehicle" icon="pi pi-plus" @click="router.push({ name: 'vehicles.create' })" />
        </div>

        <div class="surface-card">
            <DataTable
                :value="items"
                :loading="loading"
                lazy
                paginator
                :rows="filters.per_page"
                :first="first"
                :totalRecords="total"
                :rowsPerPageOptions="[10, 25, 50, 100]"
                dataKey="id"
                @page="onPage"
            >
                <template #empty>
                    <div class="empty-state"><i class="pi pi-truck" />No vehicles found.</div>
                </template>

                <Column field="number" header="Number">
                    <template #body="{ data }"><span class="cell-strong">{{ data.number }}</span></template>
                </Column>
                <Column field="vin" header="VIN">
                    <template #body="{ data }">{{ data.vin || '—' }}</template>
                </Column>
                <Column header="Unit type">
                    <template #body="{ data }">{{ unitLabel(data.unit_type_id) }}</template>
                </Column>
                <Column header="Ownership">
                    <template #body="{ data }">{{ ownerLabel(data.ownership_type_id) }}</template>
                </Column>
                <Column header="Reg. expires">
                    <template #body="{ data }">{{ fmtDate(data.reg_expire_date) }}</template>
                </Column>
                <Column header="Inspection expires">
                    <template #body="{ data }">{{ fmtDate(data.inspection_expire_date) }}</template>
                </Column>
                <Column v-if="isAdminOrManager" header="User">
                    <template #body="{ data }">{{ ownerUserLabel(data.owner) }}</template>
                </Column>
                <Column header="" style="width: 7rem">
                    <template #body="{ data }">
                        <div class="row-actions">
                            <Button icon="pi pi-pencil" text rounded v-tooltip.top="'Edit'" @click="router.push({ name: 'vehicles.edit', params: { id: data.id } })" />
                            <Button icon="pi pi-trash" text rounded severity="danger" v-tooltip.top="'Delete'" @click="confirmDelete(data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>

<style scoped>
.cell-strong { font-weight: 600; }
.row-actions { display: flex; gap: 0.1rem; }
</style>
