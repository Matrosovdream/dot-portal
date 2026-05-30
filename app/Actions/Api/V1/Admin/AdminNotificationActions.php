<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AdminNotificationActions
{
    /**
     * List notifications for the admin manager.
     *
     * Admin sees all notifications (no per-row ownership scoping).
     * Optional ?q= filters by title (plain Eloquent LIKE; SCOUT_DRIVER is null).
     */
    public function index(Request $request, User $auth): LengthAwarePaginator
    {
        $perPage = min(100, (int) $request->query('per_page', 20));

        $query = Notification::query()->latest();

        if ($q = $request->query('q')) {
            $query->where('title', 'like', '%' . $q . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a notification, forcing the creator (user_id) to the authed admin.
     */
    public function store(array $data, User $auth): Notification
    {
        // Creator is always the authenticated admin; ignore any client-sent user_id.
        $data['user_id'] = $auth->id;

        return Notification::create($data);
    }

    /**
     * Show a single notification.
     */
    public function show(Notification $notification, User $auth): Notification
    {
        $this->authorizeAccess($notification, $auth);

        return $notification;
    }

    /**
     * Update a notification. The creator (user_id) is never overwritten.
     */
    public function update(Notification $notification, array $data, User $auth): Notification
    {
        $this->authorizeAccess($notification, $auth);

        // Never overwrite the creator.
        unset($data['user_id']);

        $notification->update($data);

        return $notification->fresh();
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification, User $auth): void
    {
        $this->authorizeAccess($notification, $auth);

        $notification->delete();
    }

    /**
     * Admins and managers bypass ownership; otherwise deny.
     *
     * Mirrors the user-facing NotificationActions authorize pattern for parity.
     * For this admin-only vertical the route gate (hasRole:admin) already
     * restricts access, so this is effectively always-pass for admins.
     */
    protected function authorizeAccess(Notification $notification, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }

        abort(404);
    }
}
