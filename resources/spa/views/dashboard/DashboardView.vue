<script setup>
import { onMounted, computed } from 'vue';
import { useDashboardStore } from '@/stores/dashboard';
import { useAuthStore } from '@/stores/auth';
import Card from 'primevue/card';
import Skeleton from 'primevue/skeleton';
import Tag from 'primevue/tag';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Message from 'primevue/message';

const dashboard = useDashboardStore();
const auth = useAuthStore();

onMounted(() => dashboard.load());

const role = computed(() => dashboard.role || 'user');
const widgets = computed(() => dashboard.widgets || {});

const kpis = computed(() => widgets.value.kpis ?? []);
const recentRequests = computed(() => widgets.value.recent_requests ?? []);
const todoSummary = computed(() => widgets.value.todo_summary ?? null);
const showNewCompanyBanner = computed(() => widgets.value.banner_new_company === true);

const roleSeverity = computed(() => {
    return {
        admin: 'danger',
        manager: 'warn',
        company: 'info',
        driver: 'success',
    }[role.value] ?? 'secondary';
});

function fmtKpi(v) {
    if (typeof v === 'number') return v.toLocaleString();
    return v ?? '—';
}
</script>

<template>
    <div class="dashboard">
        <header class="dash-header">
            <div>
                <h1>Dashboard</h1>
                <p class="muted">Welcome back, {{ auth.me?.firstname || auth.me?.email }}.</p>
            </div>
            <Tag :severity="roleSeverity" :value="role.toUpperCase()" />
        </header>

        <Message v-if="dashboard.error" severity="error" :closable="false">
            {{ dashboard.error }}
        </Message>

        <Message v-if="showNewCompanyBanner" severity="info" :closable="false">
            Welcome — finish setting up your company by adding your first driver and vehicle.
        </Message>

        <section v-if="dashboard.loading" class="kpi-row">
            <Skeleton width="100%" height="6rem" v-for="i in 4" :key="i" />
        </section>

        <section v-else-if="kpis.length" class="kpi-row">
            <Card v-for="kpi in kpis" :key="kpi.key" class="kpi-card">
                <template #subtitle>{{ kpi.label }}</template>
                <template #content>
                    <div class="kpi-value">{{ fmtKpi(kpi.value) }}</div>
                    <div v-if="typeof kpi.delta === 'number'" class="kpi-delta" :class="{ neg: kpi.delta < 0 }">
                        {{ kpi.delta >= 0 ? '+' : '' }}{{ (kpi.delta * 100).toFixed(1) }}%
                    </div>
                </template>
            </Card>
        </section>

        <div class="dash-grid">
            <section v-if="todoSummary" class="dash-tile">
                <Card>
                    <template #title>Tasks</template>
                    <template #content>
                        <div class="todo-row">
                            <div>
                                <div class="todo-label">Open</div>
                                <div class="todo-value">{{ todoSummary.open }}</div>
                            </div>
                            <div>
                                <div class="todo-label">Overdue</div>
                                <div class="todo-value overdue">{{ todoSummary.overdue }}</div>
                            </div>
                        </div>
                    </template>
                </Card>
            </section>

            <section v-if="recentRequests.length" class="dash-tile wide">
                <Card>
                    <template #title>Recent requests</template>
                    <template #content>
                        <DataTable :value="recentRequests" responsiveLayout="scroll" :rows="5">
                            <Column field="id" header="ID" style="width:80px" />
                            <Column field="service_id" header="Service" />
                            <Column field="status_id" header="Status" />
                            <Column field="created_at" header="Created" />
                        </DataTable>
                    </template>
                </Card>
            </section>
        </div>

        <Message v-if="!dashboard.loading && !kpis.length && !todoSummary && !recentRequests.length"
                 severity="secondary" :closable="false">
            Nothing to show yet — your data will appear here once you start using the portal.
        </Message>
    </div>
</template>

<style scoped>
.dashboard { display: flex; flex-direction: column; gap: 1.25rem; max-width: 1200px; margin: 0 auto; }
.dash-header { display: flex; align-items: center; justify-content: space-between; }
.dash-header h1 { margin: 0; font-size: 1.6rem; color: #0f172a; }
.muted { color: #64748b; margin: 0.2rem 0 0; font-size: 0.9rem; }

.kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}
.kpi-card .kpi-value { font-size: 2rem; font-weight: 700; color: #0f172a; }
.kpi-card .kpi-delta { font-size: 0.85rem; color: #15803d; margin-top: 0.25rem; }
.kpi-card .kpi-delta.neg { color: #b91c1c; }

.dash-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1rem;
}
.dash-tile.wide { grid-column: span 1; }
@media (max-width: 768px) {
    .dash-grid { grid-template-columns: 1fr; }
    .dash-tile.wide { grid-column: span 1; }
}

.todo-row { display: flex; gap: 2rem; }
.todo-label { font-size: 0.8rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.todo-value { font-size: 1.75rem; font-weight: 700; color: #0f172a; }
.todo-value.overdue { color: #b91c1c; }
</style>
