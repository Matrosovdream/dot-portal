<script setup>
import { onMounted, computed } from 'vue';
import { useDashboardStore } from '@/stores/dashboard';
import { useAuthStore } from '@/stores/auth';
import { useReferencesStore } from '@/stores/references';
import Skeleton from 'primevue/skeleton';
import Tag from 'primevue/tag';
import Message from 'primevue/message';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import StatChart from '@/components/StatChart.vue';

const dashboard = useDashboardStore();
const auth = useAuthStore();
const references = useReferencesStore();

onMounted(() => {
    dashboard.load();
    references.load();
});

const role = computed(() => dashboard.role || 'user');
const widgets = computed(() => dashboard.widgets || {});
const kpis = computed(() => widgets.value.kpis ?? []);
const charts = computed(() => widgets.value.charts ?? []);
const recentRequests = computed(() => widgets.value.recent_requests ?? []);
const recentDocuments = computed(() => widgets.value.recent_documents ?? []);
const recentTasks = computed(() => widgets.value.recent_tasks ?? []);
const todoSummary = computed(() => widgets.value.todo_summary ?? null);
const showNewCompanyBanner = computed(() => widgets.value.banner_new_company === true);

const KPI_ICONS = {
    users: 'pi pi-users',
    companies: 'pi pi-building',
    requests: 'pi pi-inbox',
    drivers: 'pi pi-id-card',
    vehicles: 'pi pi-truck',
    revenue: 'pi pi-dollar',
    documents: 'pi pi-file',
};
const icon = (key) => KPI_ICONS[key] ?? 'pi pi-chart-bar';

// Task status is a free string (pending/open/in_progress/completed/closed) — map it locally,
// there is no reference bundle for it (request_statuses is for service requests only).
const TASK_SEVERITY = {
    pending: 'warn',
    open: 'warn',
    in_progress: 'info',
    completed: 'success',
    done: 'success',
    closed: 'secondary',
};
const taskSeverity = (status) => TASK_SEVERITY[status] ?? 'secondary';

const roleSeverity = computed(
    () => ({ admin: 'danger', manager: 'warn', company: 'info', driver: 'success' }[role.value] ?? 'secondary'),
);

const hasTables = computed(
    () => recentRequests.value.length || recentDocuments.value.length || recentTasks.value.length,
);
const isEmpty = computed(
    () =>
        !dashboard.loading &&
        !kpis.value.length &&
        !charts.value.length &&
        !todoSummary.value &&
        !hasTables.value,
);

const fmtCurrency = (v) =>
    new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(v ?? 0);

const fmtKpi = (kpi) => {
    if (kpi.format === 'currency') return fmtCurrency(kpi.value);
    return typeof kpi.value === 'number' ? kpi.value.toLocaleString() : (kpi.value ?? '—');
};
const fmtDate = (v) => (v ? new Date(v).toLocaleDateString() : '—');
const statusLabel = (id) => references.label('request_statuses', id);
</script>

<template>
    <div>
        <div class="welcome">
            <p class="text-muted">Welcome back, {{ auth.me?.firstname || auth.me?.email }}.</p>
            <Tag :severity="roleSeverity" :value="role.toUpperCase()" />
        </div>

        <Message v-if="dashboard.error" severity="error" :closable="false" class="mb">
            {{ dashboard.error }}
        </Message>

        <Message v-if="showNewCompanyBanner" severity="info" :closable="false" class="mb">
            Welcome — finish setting up your company by adding your first driver and vehicle.
        </Message>

        <!-- KPIs -->
        <div v-if="dashboard.loading" class="kpi-grid">
            <div v-for="i in 4" :key="i" class="surface-card kpi-card"><Skeleton width="100%" height="3rem" /></div>
        </div>
        <div v-else-if="kpis.length" class="kpi-grid">
            <div v-for="kpi in kpis" :key="kpi.key" class="surface-card kpi-card">
                <div class="kpi-icon"><i :class="icon(kpi.key)" /></div>
                <div>
                    <div class="kpi-value">{{ fmtKpi(kpi) }}</div>
                    <div class="kpi-label">{{ kpi.label }}</div>
                </div>
                <span v-if="kpi.delta != null && kpi.delta > 0" class="kpi-delta" title="New in the last 30 days">
                    <i class="pi pi-arrow-up" />{{ kpi.delta.toLocaleString() }}
                </span>
            </div>
        </div>

        <!-- Charts -->
        <div v-if="dashboard.loading && !charts.length" class="charts-grid">
            <div v-for="i in 3" :key="i" class="surface-card surface-card-pad">
                <Skeleton width="40%" height="1rem" class="mb" />
                <Skeleton width="100%" height="240px" />
            </div>
        </div>
        <div v-else-if="charts.length" class="charts-grid">
            <StatChart v-for="chart in charts" :key="chart.key" :config="chart" />
        </div>

        <!-- Tables loading skeleton -->
        <div v-if="dashboard.loading" class="tables-grid">
            <div v-for="i in 2" :key="'tbl' + i" class="surface-card surface-card-pad">
                <Skeleton width="40%" height="1rem" class="mb" />
                <Skeleton width="100%" height="200px" />
            </div>
        </div>

        <!-- Tasks summary (compact) -->
        <div v-if="todoSummary" class="summary-grid">
            <div class="surface-card surface-card-pad">
                <h3 class="card-title">Tasks</h3>
                <div class="todo-row">
                    <div>
                        <div class="kpi-label">Open</div>
                        <div class="todo-value">{{ todoSummary.open }}</div>
                    </div>
                    <div>
                        <div class="kpi-label">Overdue</div>
                        <div class="todo-value overdue">{{ todoSummary.overdue }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables: requests / documents / tasks -->
        <div v-if="hasTables" class="tables-grid">
            <div v-if="recentRequests.length" class="surface-card surface-card-pad">
                <h3 class="card-title">Recent requests</h3>
                <DataTable :value="recentRequests" responsiveLayout="scroll">
                    <Column field="id" header="ID" style="width: 70px" />
                    <Column header="Service">
                        <template #body="{ data }">#{{ data.service_id }}</template>
                    </Column>
                    <Column header="Status">
                        <template #body="{ data }">{{ statusLabel(data.status_id) }}</template>
                    </Column>
                    <Column header="Created">
                        <template #body="{ data }">{{ fmtDate(data.created_at) }}</template>
                    </Column>
                </DataTable>
            </div>

            <div v-if="recentDocuments.length" class="surface-card surface-card-pad">
                <h3 class="card-title">Latest documents</h3>
                <DataTable :value="recentDocuments" responsiveLayout="scroll">
                    <Column header="Name">
                        <template #body="{ data }">
                            <span class="doc-name">{{ data.name }}</span>
                            <Tag
                                v-if="data.extension"
                                class="ext-tag"
                                severity="secondary"
                                :value="String(data.extension).toUpperCase()"
                            />
                        </template>
                    </Column>
                    <Column header="Type">
                        <template #body="{ data }">{{ data.type || '—' }}</template>
                    </Column>
                    <Column header="Added">
                        <template #body="{ data }">{{ fmtDate(data.created_at) }}</template>
                    </Column>
                </DataTable>
            </div>

            <div v-if="recentTasks.length" class="surface-card surface-card-pad">
                <h3 class="card-title">My tasks</h3>
                <DataTable :value="recentTasks" responsiveLayout="scroll">
                    <Column header="Task">
                        <template #body="{ data }">{{ data.title || '—' }}</template>
                    </Column>
                    <Column header="Status">
                        <template #body="{ data }">
                            <Tag :severity="taskSeverity(data.status)" :value="data.status || '—'" />
                        </template>
                    </Column>
                    <Column header="Due">
                        <template #body="{ data }">{{ fmtDate(data.due_date) }}</template>
                    </Column>
                </DataTable>
            </div>
        </div>

        <Message v-if="isEmpty" severity="secondary" :closable="false">
            Nothing to show yet — your data will appear here once you start using the portal.
        </Message>
    </div>
</template>

<style scoped>
.welcome { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; }
.welcome p { margin: 0; }
.mb { margin-bottom: 1.25rem; }
.kpi-delta {
    margin-left: auto;
    align-self: flex-start;
    display: inline-flex;
    align-items: center;
    gap: 0.15rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #17c653;
    background: rgba(23, 198, 83, 0.12);
    padding: 0.1rem 0.4rem;
    border-radius: 999px;
}
.kpi-delta i { font-size: 0.65rem; }
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}
.summary-grid { display: flex; flex-wrap: wrap; gap: 1.25rem; margin-bottom: 1.25rem; }
.summary-grid > .surface-card { flex: 0 1 300px; }
.tables-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.25rem;
}
.card-title { margin: 0 0 1rem; font-size: 1rem; font-weight: 700; }
.todo-row { display: flex; gap: 2.5rem; }
.todo-value { font-size: 1.75rem; font-weight: 700; }
.todo-value.overdue { color: #ef4444; }
.doc-name { font-weight: 500; }
.ext-tag { margin-left: 0.4rem; font-size: 0.65rem; }
</style>
