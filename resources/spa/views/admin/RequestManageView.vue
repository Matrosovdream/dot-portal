<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import Select from 'primevue/select';
import InputNumber from 'primevue/inputnumber';
import ToggleSwitch from 'primevue/toggleswitch';
import { adminRequestsApi, unwrap, errorMessage } from '@/api';
import { useReferencesStore } from '@/stores/references';
import { normalizePage, getByPath, fmtMoney, fmtDateTime } from '@/views/crud/helpers';

const toast = useToast();
const confirm = useConfirm();
const references = useReferencesStore();

const items = ref([]);
const page = reactive({ current_page: 1, per_page: 25, total: 0 });
const loading = ref(false);
const filters = reactive({ status_id: null, user_id: null, service_id: null });

const editVisible = ref(false);
const editing = ref(null);
const original = reactive({ status_id: null });
const form = reactive({ price: null, discount_price: null, is_paid: false, status_id: null });
const saving = ref(false);

const statusOptions = computed(() => references.options('request_statuses'));
const statusLabel = (id) => references.label('request_statuses', id);

function params() {
    const p = { page: page.current_page, per_page: page.per_page };
    if (filters.status_id) p.status_id = filters.status_id;
    if (filters.user_id) p.user_id = filters.user_id;
    if (filters.service_id) p.service_id = filters.service_id;
    return p;
}
async function fetch() {
    loading.value = true;
    try {
        const norm = normalizePage(await adminRequestsApi.list(params()), page.per_page);
        items.value = norm.items;
        Object.assign(page, norm.page);
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to load', detail: errorMessage(e), life: 4000 });
    } finally {
        loading.value = false;
    }
}
onMounted(() => {
    references.load();
    fetch();
});

const first = computed(() => (page.current_page - 1) * page.per_page);
function onPage(e) {
    page.current_page = e.page + 1;
    page.per_page = e.rows;
    fetch();
}
function applyFilters() {
    page.current_page = 1;
    fetch();
}

async function openEdit(row) {
    editVisible.value = true;
    editing.value = row;
    try {
        const d = unwrap(await adminRequestsApi.get(row.id));
        form.price = d.price ?? null;
        form.discount_price = d.discount_price ?? null;
        form.is_paid = !!d.is_paid;
        form.status_id = d.status_id ?? null;
        original.status_id = d.status_id ?? null;
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
        editVisible.value = false;
    }
}

async function save() {
    saving.value = true;
    try {
        await adminRequestsApi.update(editing.value.id, {
            price: form.price,
            discount_price: form.discount_price,
            is_paid: form.is_paid,
        });
        if (form.status_id !== original.status_id && form.status_id != null) {
            await adminRequestsApi.updateStatus(editing.value.id, { status_id: form.status_id });
        }
        toast.add({ severity: 'success', summary: 'Saved', life: 3000 });
        editVisible.value = false;
        fetch();
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
    } finally {
        saving.value = false;
    }
}

function confirmDelete(row) {
    confirm.require({
        header: 'Delete request',
        message: `Delete service request #${row.id}? This cannot be undone.`,
        icon: 'pi pi-trash',
        acceptClass: 'p-button-danger',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await adminRequestsApi.remove(row.id);
                toast.add({ severity: 'success', summary: 'Deleted', life: 3000 });
                fetch();
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
            <Select v-model="filters.status_id" :options="statusOptions" optionLabel="label" optionValue="value" placeholder="Status" showClear style="min-width: 12rem" @update:modelValue="applyFilters" />
            <InputNumber v-model="filters.user_id" :useGrouping="false" placeholder="User ID" @update:modelValue="applyFilters" />
            <InputNumber v-model="filters.service_id" :useGrouping="false" placeholder="Service ID" @update:modelValue="applyFilters" />
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="fetch" />
        </div>

        <div class="surface-card">
            <DataTable :value="items" :loading="loading" lazy paginator :rows="page.per_page" :first="first" :totalRecords="page.total" :rowsPerPageOptions="[10, 25, 50, 100]" dataKey="id" @page="onPage">
                <template #empty><div class="empty-state"><i class="pi pi-inbox" />No requests found.</div></template>
                <Column field="id" header="ID" style="width: 70px" />
                <Column header="Service"><template #body="{ data }">{{ getByPath(data, 'service.name') || `#${data.service_id}` }}</template></Column>
                <Column header="Status"><template #body="{ data }"><Tag :value="statusLabel(data.status_id)" severity="info" /></template></Column>
                <Column header="Price"><template #body="{ data }">{{ fmtMoney(data.price) }}</template></Column>
                <Column header="Paid"><template #body="{ data }"><Tag :value="data.is_paid ? 'Paid' : 'Unpaid'" :severity="data.is_paid ? 'success' : 'warn'" /></template></Column>
                <Column header="Created"><template #body="{ data }">{{ fmtDateTime(data.created_at) }}</template></Column>
                <Column header="" style="width: 7rem">
                    <template #body="{ data }">
                        <div class="row-actions">
                            <Button icon="pi pi-pencil" text rounded v-tooltip.top="'Edit'" @click="openEdit(data)" />
                            <Button icon="pi pi-trash" text rounded severity="danger" v-tooltip.top="'Delete'" @click="confirmDelete(data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="editVisible" modal :header="`Request #${editing?.id ?? ''}`" :style="{ width: '32rem' }">
            <div class="form-grid">
                <div class="form-field">
                    <label>Status</label>
                    <Select v-model="form.status_id" :options="statusOptions" optionLabel="label" optionValue="value" placeholder="Status" />
                </div>
                <div class="form-field">
                    <label>Paid</label>
                    <ToggleSwitch v-model="form.is_paid" />
                </div>
                <div class="form-field">
                    <label>Price</label>
                    <InputNumber v-model="form.price" mode="currency" currency="USD" :min="0" />
                </div>
                <div class="form-field">
                    <label>Discount price</label>
                    <InputNumber v-model="form.discount_price" mode="currency" currency="USD" :min="0" />
                </div>
            </div>
            <template #footer>
                <Button label="Cancel" text @click="editVisible = false" />
                <Button label="Save" icon="pi pi-check" :loading="saving" @click="save" />
            </template>
        </Dialog>
    </div>
</template>

<style scoped>
.row-actions { display: flex; gap: 0.1rem; }
</style>
