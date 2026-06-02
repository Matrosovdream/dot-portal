<script setup>
import { onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useNotificationsStore } from '@/stores/notifications';
import Button from 'primevue/button';
import Tag from 'primevue/tag';

const store = useNotificationsStore();
const { items, unread, loading } = storeToRefs(store);

onMounted(() => store.refresh());

const fmt = (v) => (v ? new Date(v).toLocaleString() : '');
</script>

<template>
    <div>
        <div class="list-toolbar">
            <span class="text-muted">{{ unread }} unread</span>
            <span class="spacer" />
            <Button label="Refresh" icon="pi pi-refresh" text :loading="loading" @click="store.refresh()" />
            <Button
                label="Mark all read"
                icon="pi pi-check"
                outlined
                :disabled="!unread"
                @click="store.markAllRead()"
            />
        </div>

        <div class="surface-card">
            <div v-if="loading && !items.length" class="empty-state">
                <i class="pi pi-spin pi-spinner" />Loading…
            </div>
            <div v-else-if="!items.length" class="empty-state"><i class="pi pi-bell" />No notifications yet.</div>
            <ul v-else class="notif-list">
                <li v-for="n in items" :key="n.id" :class="{ unread: !n.is_read }">
                    <div class="notif-main">
                        <div class="notif-title">
                            {{ n.title || '(no title)' }}
                            <Tag v-if="!n.is_read" value="New" severity="info" />
                        </div>
                        <div class="text-muted notif-msg">{{ n.message }}</div>
                        <div class="text-muted notif-time">{{ fmt(n.created_at) }}</div>
                    </div>
                    <Button
                        v-if="!n.is_read"
                        icon="pi pi-check"
                        text
                        rounded
                        v-tooltip="'Mark read'"
                        @click="store.markRead(n.id)"
                    />
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
.notif-list { list-style: none; margin: 0; padding: 0; }
.notif-list li {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--p-content-border-color);
}
.notif-list li:last-child { border-bottom: none; }
.notif-list li.unread { background: var(--p-highlight-background); }
.notif-main { flex: 1 1 auto; }
.notif-title { font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
.notif-msg { margin: 0.2rem 0; font-size: 0.875rem; }
.notif-time { font-size: 0.78rem; }
</style>
