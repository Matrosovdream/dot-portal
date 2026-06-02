import { defineStore } from 'pinia';
import { notificationsApi } from '@/api';

export const useNotificationsStore = defineStore('notifications', {
    state: () => ({
        items: [],
        unread: 0,
        meta: null,
        loading: false,
    }),
    actions: {
        async refresh(params = {}) {
            this.loading = true;
            try {
                const body = await notificationsApi.list(params);
                this.items = body.data ?? [];
                this.unread = body.unread ?? 0;
                this.meta = body.meta ?? null;
            } catch {
                // bell is best-effort; ignore transient failures
            } finally {
                this.loading = false;
            }
        },
        async markRead(id) {
            await notificationsApi.markRead(id);
            const hit = this.items.find((n) => n.id === id);
            if (hit && !hit.is_read) {
                hit.is_read = true;
                hit.status = 'read';
                this.unread = Math.max(0, this.unread - 1);
            }
        },
        async markAllRead() {
            await notificationsApi.markAllRead();
            this.items.forEach((n) => {
                n.is_read = true;
                n.status = 'read';
            });
            this.unread = 0;
        },
    },
});
