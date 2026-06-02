<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import { errorMessage, unwrap } from '@/api';
import { normalizePage, fmtDate, fmtDateTime, toYmd, getByPath } from '@/views/crud/helpers';

const props = defineProps({
    listFn: { type: Function, required: true },
    getFn: { type: Function, required: true },
    columns: { type: Array, required: true },
    detailFields: { type: Array, required: true },
});

const items = ref([]);
const page = reactive({ current_page: 1, per_page: 25, total: 0 });
const loading = ref(false);
const filters = reactive({ report_state: '', date_from: null, date_to: null, q: '' });
let debounce;

const detail = ref(null);
const detailVisible = ref(false);

function params() {
    const p = { page: page.current_page, per_page: page.per_page };
    if (filters.report_state) p.report_state = filters.report_state;
    if (filters.date_from) p.date_from = toYmd(filters.date_from);
    if (filters.date_to) p.date_to = toYmd(filters.date_to);
    if (filters.q) p.q = filters.q;
    return p;
}

async function fetch() {
    loading.value = true;
    try {
        const norm = normalizePage(await props.listFn(params()), page.per_page);
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
function onSearch() {
    clearTimeout(debounce);
    debounce = setTimeout(applyFilters, 350);
}

function cellText(row, col) {
    const v = getByPath(row, col.key);
    if (col.type === 'date') return fmtDate(v);
    if (col.type === 'datetime') return fmtDateTime(v);
    return v ?? '—';
}

async function openDetail(row) {
    detailVisible.value = true;
    detail.value = null;
    try {
        detail.value = unwrap(await props.getFn(row.id));
    } catch (e) {
        detail.value = { error: errorMessage(e) };
    }
}
function prettyApiData(v) {
    if (v == null) return '';
    try {
        return JSON.stringify(typeof v === 'string' ? JSON.parse(v) : v, null, 2);
    } catch {
        return String(v);
    }
}
</script>

<template>
    <div>
        <div class="list-toolbar">
            <InputText v-model="filters.report_state" placeholder="State code (e.g. CA)" style="width: 11rem" @input="onSearch" />
            <DatePicker v-model="filters.date_from" dateFormat="yy-mm-dd" placeholder="From" showIcon @update:modelValue="applyFilters" />
            <DatePicker v-model="filters.date_to" dateFormat="yy-mm-dd" placeholder="To" showIcon @update:modelValue="applyFilters" />
            <InputText v-model="filters.q" placeholder="VIN / Report # / DOT #" @input="onSearch" />
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="fetch" />
        </div>

        <DataTable
            :value="items"
            :loading="loading"
            lazy
            paginator
            :rows="page.per_page"
            :first="first"
            :totalRecords="page.total"
            :rowsPerPageOptions="[10, 25, 50, 100]"
            dataKey="id"
            @page="onPage"
        >
            <template #empty><div class="empty-state"><i class="pi pi-search-plus" />No records found.</div></template>
            <Column v-for="col in columns" :key="col.key" :header="col.label">
                <template #body="{ data }">
                    <Tag v-if="col.type === 'badge'" :value="getByPath(data, col.key) ?? '—'" severity="info" />
                    <span v-else>{{ cellText(data, col) }}</span>
                </template>
            </Column>
            <Column header="" style="width: 5rem">
                <template #body="{ data }">
                    <Button icon="pi pi-eye" text rounded v-tooltip.top="'View'" @click="openDetail(data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="detailVisible" modal header="Record detail" :style="{ width: '40rem' }">
            <div v-if="!detail" class="text-muted"><i class="pi pi-spin pi-spinner" /> Loading…</div>
            <template v-else>
                <dl class="detail-grid">
                    <template v-for="f in detailFields" :key="f">
                        <template v-if="f !== 'api_data'">
                            <dt>{{ f }}</dt>
                            <dd>{{ getByPath(detail, f) ?? '—' }}</dd>
                        </template>
                    </template>
                </dl>
                <div v-if="detail.api_data" class="api-data">
                    <div class="text-muted">api_data</div>
                    <pre>{{ prettyApiData(detail.api_data) }}</pre>
                </div>
            </template>
        </Dialog>
    </div>
</template>

<style scoped>
.detail-grid { display: grid; grid-template-columns: 11rem 1fr; gap: 0.4rem 1rem; margin: 0; }
.detail-grid dt { font-weight: 600; color: var(--p-text-muted-color); font-size: 0.85rem; }
.detail-grid dd { margin: 0; word-break: break-word; }
.api-data { margin-top: 1rem; }
.api-data pre {
    background: var(--p-surface-100);
    padding: 0.75rem;
    border-radius: 8px;
    max-height: 16rem;
    overflow: auto;
    font-size: 0.78rem;
}
</style>
