<script setup>
import { computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import { useServiceRequestsStore } from '@/stores/serviceRequests';
import { useReferencesStore } from '@/stores/references';

const router = useRouter();
const store = useServiceRequestsStore();
const references = useReferencesStore();
const { items, meta, loading, filters } = storeToRefs(store);

onMounted(() => {
    references.load();
    store.fetch();
});

const first = computed(() => ((meta.value?.current_page ?? 1) - 1) * (meta.value?.per_page ?? filters.value.per_page));
const total = computed(() => meta.value?.total ?? 0);

function onPage(e) {
    store.fetch({ page: e.page + 1, per_page: e.rows });
}
const statusLabel = (id) => references.label('request_statuses', id);
const money = (v) => `$${Number(v ?? 0).toFixed(2)}`;
const fmtDate = (v) => (v ? new Date(v).toLocaleDateString() : '—');
</script>

<template>
    <div>
        <div class="list-toolbar">
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="store.fetch()" />
            <Button label="New Request" icon="pi pi-plus" @click="router.push({ name: 'service-requests.create' })" />
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
                    <div class="empty-state"><i class="pi pi-file-edit" />No service requests yet.</div>
                </template>

                <Column field="id" header="ID" style="width: 70px" />
                <Column header="Service">
                    <template #body="{ data }">
                        <span class="cell-strong">{{ data.service?.name || `#${data.service_id}` }}</span>
                    </template>
                </Column>
                <Column header="Price">
                    <template #body="{ data }">{{ money(data.price) }}</template>
                </Column>
                <Column header="Paid">
                    <template #body="{ data }">
                        <Tag :value="data.is_paid ? 'Paid' : 'Unpaid'" :severity="data.is_paid ? 'success' : 'warn'" />
                    </template>
                </Column>
                <Column header="Status">
                    <template #body="{ data }">{{ statusLabel(data.status_id) }}</template>
                </Column>
                <Column header="Created">
                    <template #body="{ data }">{{ fmtDate(data.created_at) }}</template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>

<style scoped>
.cell-strong { font-weight: 600; }
</style>
