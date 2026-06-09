<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Select from 'primevue/select';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import UserSelect from '@/components/UserSelect.vue';
import { useDriversStore } from '@/stores/drivers';
import { useAuthStore } from '@/stores/auth';
import { driversApi, errorMessage } from '@/api';

const router = useRouter();
const toast = useToast();
const confirm = useConfirm();
const store = useDriversStore();
const auth = useAuthStore();
const { items, meta, loading, filters } = storeToRefs(store);

const isAdminOrManager = computed(() => auth.isAdmin || auth.isManager);
const ownerLabel = (o) => (o ? `${o.fullname} (${o.email})` : '—');

const STATUS = { 1: { label: 'Active', severity: 'success' }, 2: { label: 'Inactive', severity: 'warn' }, 3: { label: 'Terminated', severity: 'danger' } };
const statusOptions = [
    { label: 'All statuses', value: null },
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' },
    { label: 'Terminated', value: 'terminated' },
];

const search = ref('');
let debounce;

onMounted(() => store.fetch());

const first = computed(() => ((meta.value?.current_page ?? 1) - 1) * (meta.value?.per_page ?? filters.value.per_page));
const total = computed(() => meta.value?.total ?? 0);

function onSearch() {
    clearTimeout(debounce);
    debounce = setTimeout(() => store.fetch({ q: search.value, page: 1 }), 350);
}
function onStatus(value) {
    store.fetch({ status: value, page: 1 });
}
function onPage(e) {
    store.fetch({ page: e.page + 1, per_page: e.rows });
}
function fmtDate(v) {
    return v ? new Date(v).toLocaleDateString() : '—';
}

async function sendOnetime(d) {
    try {
        await driversApi.sendOnetime(d.id);
        toast.add({ severity: 'success', summary: 'Sent', detail: 'One-time login link sent.', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
    }
}

function confirmTerminate(d) {
    confirm.require({
        header: 'Terminate driver',
        message: `Terminate ${d.fullname || d.email}? They will be marked terminated.`,
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        acceptLabel: 'Terminate',
        accept: async () => {
            try {
                await driversApi.terminate(d.id);
                toast.add({ severity: 'success', summary: 'Terminated', life: 3000 });
                store.fetch();
            } catch (e) {
                toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
            }
        },
    });
}

function confirmDelete(d) {
    confirm.require({
        header: 'Delete driver',
        message: `Permanently delete ${d.fullname || d.email}? This cannot be undone.`,
        icon: 'pi pi-trash',
        acceptClass: 'p-button-danger',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await driversApi.remove(d.id);
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
                <InputText v-model="search" placeholder="Search name or email" @input="onSearch" />
            </IconField>
            <Select
                :options="statusOptions"
                optionLabel="label"
                optionValue="value"
                :modelValue="filters.status"
                @update:modelValue="onStatus"
                style="min-width: 12rem"
            />
            <UserSelect
                v-if="isAdminOrManager"
                :modelValue="filters.user_id"
                placeholder="Filter by user"
                @update:modelValue="(v) => store.fetch({ user_id: v, page: 1 })"
            />
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="store.fetch()" />
            <Button label="New Driver" icon="pi pi-plus" @click="router.push({ name: 'drivers.create' })" />
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
                    <div class="empty-state"><i class="pi pi-id-card" />No drivers found.</div>
                </template>

                <Column header="Name">
                    <template #body="{ data }">
                        <div class="cell-strong">{{ data.fullname || `${data.firstname} ${data.lastname}` }}</div>
                    </template>
                </Column>
                <Column field="email" header="Email" />
                <Column field="phone" header="Phone">
                    <template #body="{ data }">{{ data.phone || '—' }}</template>
                </Column>
                <Column header="Status">
                    <template #body="{ data }">
                        <Tag
                            :value="STATUS[data.status_id]?.label ?? 'Unknown'"
                            :severity="STATUS[data.status_id]?.severity ?? 'secondary'"
                        />
                    </template>
                </Column>
                <Column header="Hire date">
                    <template #body="{ data }">{{ fmtDate(data.hire_date) }}</template>
                </Column>
                <Column v-if="isAdminOrManager" header="User">
                    <template #body="{ data }">{{ ownerLabel(data.owner) }}</template>
                </Column>
                <Column header="" style="width: 12rem">
                    <template #body="{ data }">
                        <div class="row-actions">
                            <Button
                                icon="pi pi-pencil"
                                text
                                rounded
                                v-tooltip.top="'Edit'"
                                @click="router.push({ name: 'drivers.edit', params: { id: data.id } })"
                            />
                            <Button
                                icon="pi pi-send"
                                text
                                rounded
                                severity="help"
                                v-tooltip.top="'Send one-time login'"
                                @click="sendOnetime(data)"
                            />
                            <Button
                                icon="pi pi-ban"
                                text
                                rounded
                                severity="warn"
                                v-tooltip.top="'Terminate'"
                                :disabled="data.status_id === 3"
                                @click="confirmTerminate(data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                text
                                rounded
                                severity="danger"
                                v-tooltip.top="'Delete'"
                                @click="confirmDelete(data)"
                            />
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
