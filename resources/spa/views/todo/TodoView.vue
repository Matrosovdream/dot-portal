<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import Select from 'primevue/select';
import ToggleSwitch from 'primevue/toggleswitch';
import { todoApi, unwrap, errorMessage } from '@/api';
import { normalizePage, getByPath, fmtDate, fmtDateTime } from '@/views/crud/helpers';

const items = ref([]);
const page = reactive({ current_page: 1, per_page: 20, total: 0 });
const loading = ref(false);
const filters = reactive({ entity: null, status: null, overdue: false });

const detail = ref(null);
const detailVisible = ref(false);

const entityOptions = [
    { label: 'All', value: null },
    { label: 'Company', value: 'company' },
    { label: 'Vehicle', value: 'vehicle' },
    { label: 'Driver', value: 'driver' },
];
const statusOptions = [
    { label: 'All', value: null },
    { label: 'Open', value: 'open' },
    { label: 'In Progress', value: 'in_progress' },
    { label: 'Completed', value: 'completed' },
];
const STATUS_SEVERITY = { open: 'warn', in_progress: 'info', completed: 'success' };

function params() {
    const p = { page: page.current_page, per_page: page.per_page };
    if (filters.status) p.status = filters.status;
    if (filters.overdue) p.overdue = 1;
    return p;
}
async function fetch() {
    loading.value = true;
    try {
        const fn =
            filters.entity === 'company' ? todoApi.company
            : filters.entity === 'vehicle' ? todoApi.vehicle
            : filters.entity === 'driver' ? todoApi.driver
            : todoApi.list;
        const norm = normalizePage(await fn(params()), page.per_page);
        items.value = norm.items;
        Object.assign(page, norm.page);
    } catch (e) {
        items.value = [];
    } finally {
        loading.value = false;
    }
}
onMounted(fetch);

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

async function openDetail(row) {
    detailVisible.value = true;
    detail.value = null;
    try {
        detail.value = unwrap(await todoApi.get(row.id));
    } catch (e) {
        detail.value = { title: 'Error', description: errorMessage(e) };
    }
}

const detailFields = ['title', 'description', 'category', 'subcategory', 'status', 'priority', 'due_date', 'completed_at', 'entity', 'link', 'created_at'];
</script>

<template>
    <div>
        <div class="list-toolbar">
            <Select v-model="filters.entity" :options="entityOptions" optionLabel="label" optionValue="value" placeholder="Entity" style="min-width: 10rem" @update:modelValue="applyFilters" />
            <Select v-model="filters.status" :options="statusOptions" optionLabel="label" optionValue="value" placeholder="Status" style="min-width: 11rem" @update:modelValue="applyFilters" />
            <span class="filter-switch"><ToggleSwitch v-model="filters.overdue" @update:modelValue="applyFilters" /><label>Overdue only</label></span>
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="fetch" />
        </div>

        <div class="surface-card">
            <DataTable :value="items" :loading="loading" lazy paginator :rows="page.per_page" :first="first" :totalRecords="page.total" :rowsPerPageOptions="[10, 20, 50, 100]" dataKey="id" @page="onPage">
                <template #empty><div class="empty-state"><i class="pi pi-check-square" />No tasks found.</div></template>
                <Column header="Title"><template #body="{ data }"><span class="cell-strong">{{ data.title }}</span></template></Column>
                <Column field="category" header="Category"><template #body="{ data }">{{ data.category || '—' }}</template></Column>
                <Column header="Status"><template #body="{ data }"><Tag :value="data.status || '—'" :severity="STATUS_SEVERITY[data.status] || 'secondary'" /></template></Column>
                <Column header="Priority"><template #body="{ data }"><Tag v-if="data.priority" :value="data.priority" severity="contrast" /><span v-else>—</span></template></Column>
                <Column field="entity" header="Entity"><template #body="{ data }">{{ data.entity || '—' }}</template></Column>
                <Column header="Due"><template #body="{ data }">{{ fmtDate(data.due_date) }}</template></Column>
                <Column header="" style="width: 5rem"><template #body="{ data }"><Button icon="pi pi-eye" text rounded v-tooltip.top="'View'" @click="openDetail(data)" /></template></Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="detailVisible" modal header="Task" :style="{ width: '36rem' }">
            <div v-if="!detail" class="text-muted"><i class="pi pi-spin pi-spinner" /> Loading…</div>
            <dl v-else class="detail-grid">
                <template v-for="f in detailFields" :key="f">
                    <dt>{{ f }}</dt>
                    <dd>{{ f.endsWith('_at') || f === 'due_date' ? (getByPath(detail, f) ? fmtDateTime(getByPath(detail, f)) : '—') : (getByPath(detail, f) ?? '—') }}</dd>
                </template>
            </dl>
        </Dialog>
    </div>
</template>

<style scoped>
.cell-strong { font-weight: 600; }
.filter-switch { display: inline-flex; align-items: center; gap: 0.5rem; }
.filter-switch label { font-size: 0.85rem; color: var(--p-text-muted-color); }
.detail-grid { display: grid; grid-template-columns: 9rem 1fr; gap: 0.4rem 1rem; margin: 0; }
.detail-grid dt { font-weight: 600; color: var(--p-text-muted-color); font-size: 0.85rem; }
.detail-grid dd { margin: 0; word-break: break-word; }
</style>
