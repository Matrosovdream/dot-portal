<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import DatePicker from 'primevue/datepicker';
import ToggleSwitch from 'primevue/toggleswitch';
import { useReferencesStore } from '@/stores/references';
import { errorMessage } from '@/api';
import { sections } from './configs';
import { getByPath, normalizePage, resolveOptions, fmtDate, fmtDateTime, fmtMoney } from './helpers';

const route = useRoute();
const router = useRouter();
const toast = useToast();
const confirm = useConfirm();
const references = useReferencesStore();

const cfg = computed(() => sections[route.meta.section]);

const items = ref([]);
const page = reactive({ current_page: 1, last_page: 1, per_page: 25, total: 0 });
const loading = ref(false);
const filterValues = reactive({});
let debounce;

function initFilters() {
    (cfg.value.filters ?? []).forEach((f) => {
        if (!(f.key in filterValues)) filterValues[f.key] = null;
    });
    page.per_page = cfg.value.perPage ?? 25;
}

function buildParams(extra = {}) {
    const params = { page: page.current_page, per_page: page.per_page, ...extra };
    (cfg.value.filters ?? []).forEach((f) => {
        const v = filterValues[f.key];
        if (v !== null && v !== '' && v !== undefined) params[f.key] = v;
    });
    return params;
}

async function fetch(extra = {}) {
    loading.value = true;
    try {
        const body = await cfg.value.api.list(buildParams(extra));
        const norm = normalizePage(body, page.per_page);
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
    initFilters();
    fetch();
});

// Reset to the section's data when navigating between CRUD sections (shared component).
watch(
    () => route.meta.section,
    () => {
        Object.keys(filterValues).forEach((k) => delete filterValues[k]);
        page.current_page = 1;
        initFilters();
        fetch();
    },
);

const first = computed(() => (page.current_page - 1) * page.per_page);

function onPage(e) {
    page.current_page = e.page + 1;
    page.per_page = e.rows;
    fetch();
}
function onFilterChange() {
    page.current_page = 1;
    fetch();
}
function onSearchInput() {
    clearTimeout(debounce);
    debounce = setTimeout(onFilterChange, 350);
}

function cell(row, col) {
    const raw = getByPath(row, col.key);
    switch (col.type) {
        case 'money':
            return fmtMoney(raw);
        case 'date':
            return fmtDate(raw);
        case 'datetime':
            return fmtDateTime(raw);
        case 'boolean':
            return raw ? 'Yes' : 'No';
        default:
            return raw ?? '—';
    }
}
function badgeLabel(row, col) {
    const raw = getByPath(row, col.key);
    return col.ref ? references.label(col.ref, raw) : (raw ?? '—');
}

function filterOptions(f) {
    return resolveOptions(f.options) ?? [];
}

function goNew() {
    router.push(`${cfg.value.routeBase}/new`);
}
function goEdit(row) {
    router.push(`${cfg.value.routeBase}/${row.id}`);
}
function openRow(row) {
    const url = row[cfg.value.rowOpenField];
    if (url) window.open(url, '_blank', 'noopener');
}
function confirmDelete(row) {
    confirm.require({
        header: `Delete ${cfg.value.singular}`,
        message: 'This cannot be undone. Continue?',
        icon: 'pi pi-trash',
        acceptClass: 'p-button-danger',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await cfg.value.api.remove(row.id);
                toast.add({ severity: 'success', summary: 'Deleted', life: 3000 });
                fetch();
            } catch (e) {
                toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
            }
        },
    });
}

const hasRowActions = computed(() => cfg.value.canEdit || cfg.value.canDelete || cfg.value.rowOpenField);
</script>

<template>
    <div>
        <div class="list-toolbar">
            <template v-for="f in cfg.filters" :key="f.key">
                <InputText
                    v-if="f.type === 'text'"
                    v-model="filterValues[f.key]"
                    :placeholder="f.label"
                    @input="onSearchInput"
                />
                <Select
                    v-else-if="f.type === 'select'"
                    v-model="filterValues[f.key]"
                    :options="filterOptions(f)"
                    optionLabel="label"
                    optionValue="value"
                    :placeholder="f.label"
                    showClear
                    style="min-width: 12rem"
                    @update:modelValue="onFilterChange"
                />
                <InputNumber
                    v-else-if="f.type === 'number'"
                    v-model="filterValues[f.key]"
                    :useGrouping="false"
                    :placeholder="f.label"
                    @update:modelValue="onSearchInput"
                />
                <DatePicker
                    v-else-if="f.type === 'date'"
                    v-model="filterValues[f.key]"
                    dateFormat="yy-mm-dd"
                    :placeholder="f.label"
                    showIcon
                    @update:modelValue="onFilterChange"
                />
                <span v-else-if="f.type === 'switch'" class="filter-switch">
                    <ToggleSwitch v-model="filterValues[f.key]" @update:modelValue="onFilterChange" />
                    <label>{{ f.label }}</label>
                </span>
            </template>

            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="fetch()" />
            <Button v-if="cfg.canCreate" :label="`New ${cfg.singular}`" icon="pi pi-plus" @click="goNew" />
        </div>

        <div class="surface-card">
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
                <template #empty>
                    <div class="empty-state"><i :class="cfg.icon || 'pi pi-inbox'" />No {{ cfg.title.toLowerCase() }} found.</div>
                </template>

                <Column v-for="col in cfg.columns" :key="col.key" :header="col.label">
                    <template #body="{ data }">
                        <Tag
                            v-if="col.type === 'badge' || col.type === 'boolean'"
                            :value="col.type === 'boolean' ? (getByPath(data, col.key) ? 'Yes' : 'No') : badgeLabel(data, col)"
                            :severity="col.type === 'boolean' ? (getByPath(data, col.key) ? 'success' : 'secondary') : 'info'"
                        />
                        <a v-else-if="col.type === 'email'" :href="`mailto:${getByPath(data, col.key)}`">{{ cell(data, col) }}</a>
                        <span v-else>{{ cell(data, col) }}</span>
                    </template>
                </Column>

                <Column v-if="hasRowActions" header="" style="width: 9rem">
                    <template #body="{ data }">
                        <div class="row-actions">
                            <Button
                                v-if="cfg.rowOpenField"
                                icon="pi pi-external-link"
                                text
                                rounded
                                v-tooltip.top="'Open'"
                                :disabled="!data[cfg.rowOpenField]"
                                @click="openRow(data)"
                            />
                            <Button v-if="cfg.canEdit" icon="pi pi-pencil" text rounded v-tooltip.top="'Edit'" @click="goEdit(data)" />
                            <Button
                                v-if="cfg.canDelete"
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
.row-actions { display: flex; gap: 0.1rem; }
.filter-switch { display: inline-flex; align-items: center; gap: 0.5rem; }
.filter-switch label { font-size: 0.85rem; color: var(--p-text-muted-color); }
</style>
