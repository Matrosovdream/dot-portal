<?php

namespace App\Actions\Api\V1\Todo;

use App\Models\User;
use App\Models\UserTask;

class TodoActions
{
    public function index($request, User $auth)
    {
        return $this->baseQuery($request, $auth)->paginate($this->perPage($request));
    }

    public function listByEntity($request, User $auth, string $entity)
    {
        return $this->baseQuery($request, $auth)
            ->where('entity', $entity)
            ->paginate($this->perPage($request));
    }

    public function show(UserTask $task, User $auth): UserTask
    {
        $this->authorizeAccess($task, $auth);

        return $task->load(['meta', 'user', 'userAssigned']);
    }

    protected function baseQuery($request, User $auth)
    {
        $query = UserTask::query()->with(['meta', 'user']);

        $this->scopeToUser($query, $auth);

        // Admin/manager may narrow the cross-user list to a single owner account.
        if (($auth->isAdmin() || $auth->isManager()) && ($ownerId = $request->query('user_id'))) {
            $query->where('user_id', (int) $ownerId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($request->boolean('overdue')) {
            $query->whereNotNull('due_date')->where('due_date', '<', now());
        }

        return $query->latest();
    }

    protected function perPage($request): int
    {
        return min(100, (int) $request->query('per_page', 20));
    }

    protected function scopeToUser($query, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }

        $query->where('user_id', $auth->id);
    }

    protected function authorizeAccess(UserTask $task, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }

        if ((int) $task->user_id === (int) $auth->id) {
            return;
        }

        abort(404);
    }
}
