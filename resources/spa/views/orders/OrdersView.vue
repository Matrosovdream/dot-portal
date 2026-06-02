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
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import { ordersApi, unwrap, errorMessage } from '@/api';
import { useReferencesStore } from '@/stores/references';
import { normalizePage, getByPath, fmtMoney, fmtDateTime } from '@/views/crud/helpers';

const toast = useToast();
const confirm = useConfirm();
const references = useReferencesStore();

const items = ref([]);
const page = reactive({ current_page: 1, per_page: 25, total: 0 });
const loading = ref(false);
const filters = reactive({ q: '', status_id: null, payment_method_id: null });
let debounce;

const detail = ref(null);
const detailVisible = ref(false);
const paying = ref(false);

const statusOptions = computed(() => references.options('order_statuses'));

function params() {
    const p = { page: page.current_page, per_page: page.per_page };
    if (filters.q) p.q = filters.q;
    if (filters.status_id) p.status_id = filters.status_id;
    if (filters.payment_method_id) p.payment_method_id = filters.payment_method_id;
    return p;
}
async function fetch() {
    loading.value = true;
    try {
        const norm = normalizePage(await ordersApi.list(params()), page.per_page);
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
function onSearch() {
    clearTimeout(debounce);
    debounce = setTimeout(applyFilters, 350);
}

async function openDetail(row) {
    detailVisible.value = true;
    detail.value = null;
    try {
        detail.value = unwrap(await ordersApi.get(row.id));
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
        detailVisible.value = false;
    }
}

const isPaid = computed(() => {
    const s = (getByPath(detail.value, 'status.name') || '').toLowerCase();
    return s.includes('paid') || detail.value?.status_id === 3;
});

function confirmPay() {
    confirm.require({
        header: 'Pay order',
        message: `Charge ${fmtMoney(detail.value?.amount)} for order ${detail.value?.order_number}?`,
        icon: 'pi pi-credit-card',
        acceptLabel: 'Pay',
        accept: async () => {
            paying.value = true;
            try {
                await ordersApi.pay(detail.value.id, {});
                toast.add({ severity: 'success', summary: 'Paid', detail: 'Payment succeeded.', life: 3000 });
                detail.value = unwrap(await ordersApi.get(detail.value.id));
                fetch();
            } catch (e) {
                toast.add({ severity: 'error', summary: 'Payment failed', detail: errorMessage(e), life: 5000 });
            } finally {
                paying.value = false;
            }
        },
    });
}
</script>

<template>
    <div>
        <div class="list-toolbar">
            <InputText v-model="filters.q" placeholder="Order #" @input="onSearch" />
            <Select
                v-model="filters.status_id"
                :options="statusOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="Status"
                showClear
                style="min-width: 12rem"
                @update:modelValue="applyFilters"
            />
            <InputNumber v-model="filters.payment_method_id" :useGrouping="false" placeholder="Payment method ID" @update:modelValue="onSearch" />
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="fetch" />
        </div>

        <div class="surface-card">
            <DataTable :value="items" :loading="loading" lazy paginator :rows="page.per_page" :first="first" :totalRecords="page.total" :rowsPerPageOptions="[10, 25, 50, 100]" dataKey="id" @page="onPage">
                <template #empty><div class="empty-state"><i class="pi pi-shopping-cart" />No orders found.</div></template>
                <Column header="Order #"><template #body="{ data }"><span class="cell-strong">{{ data.order_number }}</span></template></Column>
                <Column header="Customer"><template #body="{ data }">{{ getByPath(data, 'user.email') || '—' }}</template></Column>
                <Column header="Amount"><template #body="{ data }">{{ fmtMoney(data.amount) }}</template></Column>
                <Column header="Status"><template #body="{ data }"><Tag :value="getByPath(data, 'status.name') || '—'" severity="info" /></template></Column>
                <Column header="Payment"><template #body="{ data }">{{ getByPath(data, 'payment_method.name') || '—' }}</template></Column>
                <Column header="Created"><template #body="{ data }">{{ fmtDateTime(data.created_at) }}</template></Column>
                <Column header="" style="width: 5rem"><template #body="{ data }"><Button icon="pi pi-eye" text rounded v-tooltip.top="'View'" @click="openDetail(data)" /></template></Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="detailVisible" modal header="Order" :style="{ width: '46rem' }">
            <div v-if="!detail" class="text-muted"><i class="pi pi-spin pi-spinner" /> Loading…</div>
            <template v-else>
                <div class="order-head">
                    <div>
                        <div class="text-muted">Order</div>
                        <h3 class="order-no">{{ detail.order_number }}</h3>
                        <div class="text-muted">{{ getByPath(detail, 'user.firstname') }} {{ getByPath(detail, 'user.lastname') }} · {{ getByPath(detail, 'user.email') }}</div>
                    </div>
                    <div class="order-amount">
                        <Tag :value="getByPath(detail, 'status.name') || '—'" severity="info" />
                        <div class="amount">{{ fmtMoney(detail.amount) }}</div>
                    </div>
                </div>

                <h4 class="sec">Items</h4>
                <DataTable :value="detail.items || []" size="small">
                    <template #empty><span class="text-muted">No items.</span></template>
                    <Column field="item_name" header="Item" />
                    <Column field="quantity" header="Qty" />
                    <Column header="Unit"><template #body="{ data }">{{ fmtMoney(data.unit_price) }}</template></Column>
                    <Column header="Total"><template #body="{ data }">{{ fmtMoney(data.total_price) }}</template></Column>
                </DataTable>

                <h4 class="sec">Payments</h4>
                <DataTable :value="detail.payments || []" size="small">
                    <template #empty><span class="text-muted">No payments yet.</span></template>
                    <Column header="Amount"><template #body="{ data }">{{ fmtMoney(data.amount) }}</template></Column>
                    <Column field="status" header="Status" />
                    <Column field="transaction_id" header="Transaction" />
                    <Column header="Date"><template #body="{ data }">{{ fmtDateTime(data.payment_date) }}</template></Column>
                </DataTable>
            </template>

            <template #footer>
                <Button label="Close" text @click="detailVisible = false" />
                <Button v-if="detail && !isPaid" label="Pay order" icon="pi pi-credit-card" :loading="paying" @click="confirmPay" />
            </template>
        </Dialog>
    </div>
</template>

<style scoped>
.cell-strong { font-weight: 600; }
.order-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
.order-no { margin: 0.1rem 0; }
.order-amount { text-align: right; }
.order-amount .amount { font-size: 1.4rem; font-weight: 700; margin-top: 0.25rem; }
.sec { margin: 1.25rem 0 0.5rem; font-size: 0.95rem; }
</style>
