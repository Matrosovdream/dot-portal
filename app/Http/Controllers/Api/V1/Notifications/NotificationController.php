<?php

namespace App\Http\Controllers\Api\V1\Notifications;

use App\Actions\Api\V1\Notifications\NotificationActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Notifications\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationActions $actions) {}

    public function index(Request $request)
    {
        $auth = $request->user();

        return NotificationResource::collection($this->actions->index($request, $auth))
            ->additional(['unread' => $this->actions->unreadCount($auth)]);
    }

    public function markRead(Request $request, Notification $notification)
    {
        return new NotificationResource($this->actions->markRead($notification, $request->user()));
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $updated = $this->actions->markAllRead($request->user());

        return response()->json([
            'message' => 'All notifications marked read.',
            'updated' => $updated,
        ]);
    }
}
