<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Message from 'primevue/message';
import { subscriptionApi, unwrap, fieldErrors, errorMessage } from '@/api';

const toast = useToast();
const confirm = useConfirm();

const sub = ref(null);
const loading = ref(true);
const submitting = ref(false);
const errors = ref({});

const form = reactive({ subscription_id: null, drivers_number: null, payment_card_id: null });

const statusSeverity = computed(() => {
    const s = sub.value?.status;
    return { active: 'success', trial: 'info', cancelled: 'danger', pending: 'warn' }[s] ?? 'secondary';
});
const planName = computed(() => sub.value?.subscription?.name ?? '—');
const money = (v) => (v == null ? '—' : `$${Number(v).toFixed(2)}`);
const date = (v) => (v ? new Date(v).toLocaleDateString() : '—');

async function load() {
    loading.value = true;
    try {
        sub.value = unwrap(await subscriptionApi.get());
        if (sub.value) {
            form.subscription_id = sub.value.subscription_id ?? null;
            form.drivers_number = sub.value.drivers_number ?? null;
            form.payment_card_id = sub.value.payment_card_id ?? null;
        }
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Load failed', detail: errorMessage(e), life: 4000 });
    } finally {
        loading.value = false;
    }
}
onMounted(load);

async function save() {
    errors.value = {};
    submitting.value = true;
    try {
        await subscriptionApi.update({ ...form });
        toast.add({ severity: 'success', summary: 'Saved', detail: 'Subscription updated.', life: 3000 });
        load();
    } catch (e) {
        if (e.response?.status === 422) errors.value = fieldErrors(e);
        else toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
    } finally {
        submitting.value = false;
    }
}

function cancelSub() {
    confirm.require({
        header: 'Cancel subscription',
        message: 'Your subscription will be cancelled. Continue?',
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        acceptLabel: 'Cancel subscription',
        rejectLabel: 'Keep',
        accept: async () => {
            try {
                await subscriptionApi.cancel();
                toast.add({ severity: 'success', summary: 'Cancelled', life: 3000 });
                load();
            } catch (e) {
                toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
            }
        },
    });
}

const err = (n) => errors.value[n]?.[0];
const canCancel = computed(() => ['active', 'trial'].includes(sub.value?.status));
</script>

<template>
    <div>
        <Message v-if="loading" severity="secondary" :closable="false">Loading…</Message>
        <Message v-else-if="!sub" severity="info" :closable="false">You don't have a subscription yet.</Message>

        <template v-else>
            <div class="surface-card surface-card-pad current">
                <div class="current-head">
                    <div>
                        <div class="text-muted">Current plan</div>
                        <h2 class="plan-name">{{ planName }}</h2>
                    </div>
                    <Tag :value="(sub.status || 'unknown').toUpperCase()" :severity="statusSeverity" />
                </div>
                <div class="kpi-grid">
                    <div><div class="kpi-label">Price</div><div class="stat">{{ money(sub.price) }}</div></div>
                    <div><div class="kpi-label">Per driver</div><div class="stat">{{ money(sub.price_per_driver) }}</div></div>
                    <div><div class="kpi-label">Drivers</div><div class="stat">{{ sub.drivers_number ?? '—' }}</div></div>
                    <div><div class="kpi-label">Next billing</div><div class="stat">{{ date(sub.next_date) }}</div></div>
                </div>
            </div>

            <div class="surface-card surface-card-pad mt">
                <h3 class="card-title">Change plan</h3>
                <form class="form-grid" @submit.prevent="save">
                    <div class="form-field">
                        <label>Plan ID *</label>
                        <InputNumber v-model="form.subscription_id" :useGrouping="false" :min="1" :invalid="!!err('subscription_id')" />
                        <small v-if="err('subscription_id')" class="field-error">{{ err('subscription_id') }}</small>
                    </div>
                    <div class="form-field">
                        <label>Number of drivers</label>
                        <InputNumber v-model="form.drivers_number" :min="0" />
                    </div>
                    <div class="form-field">
                        <label>Payment card ID</label>
                        <InputNumber v-model="form.payment_card_id" :useGrouping="false" :min="1" />
                    </div>
                    <div class="form-field col-span-2 form-actions">
                        <Button v-if="canCancel" label="Cancel subscription" severity="danger" outlined type="button" @click="cancelSub" />
                        <Button type="submit" label="Save changes" icon="pi pi-check" :loading="submitting" />
                    </div>
                </form>
            </div>
        </template>
    </div>
</template>

<style scoped>
.current-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.25rem; }
.plan-name { margin: 0.15rem 0 0; font-size: 1.4rem; }
.stat { font-size: 1.25rem; font-weight: 700; }
.mt { margin-top: 1.25rem; }
.card-title { margin: 0 0 1rem; font-size: 1rem; font-weight: 700; }
.form-actions { justify-content: space-between; }
</style>
