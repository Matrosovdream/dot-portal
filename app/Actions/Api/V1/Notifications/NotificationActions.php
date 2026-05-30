<?php

namespace App\Actions\Api\V1\Notifications;

use App\Models\Notification;
use App\Models\User;

class NotificationActions
{
    public function index($request, User $auth)
    {
        $query = Notification::query()->where('user_id_to', $auth->id);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $perPage = min(100, (int) $request->query('per_page', 15));

        return $query->latest()->paginate($perPage);
    }

    public function unreadCount(User $auth): int
    {
        return Notification::query()
            ->where('user_id_to', $auth->id)
            ->where('status', '!=', 'read')
            ->count();
    }

    public function markRead(Notification $notification, User $auth): Notification
    {
        $this->authorizeAccess($notification, $auth);
        $notification->update(['status' => 'read']);

        return $notification->fresh();
    }

    public function markAllRead(User $auth): int
    {
        return Notification::query()
            ->where('user_id_to', $auth->id)
            ->where('status', '!=', 'read')
            ->update(['status' => 'read']);
    }

    protected function authorizeAccess(Notification $notification, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }

        if ((int) $notification->user_id_to === (int) $auth->id) {
            return;
        }

        abort(404);
    }
}
