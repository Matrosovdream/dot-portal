<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\AdminNotificationActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\StoreAdminNotificationRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminNotificationRequest;
use App\Http\Resources\V1\Admin\AdminNotificationListItemResource;
use App\Http\Resources\V1\Admin\AdminNotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminNotificationController extends Controller
{
    public function __construct(
        private readonly AdminNotificationActions $actions,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return AdminNotificationListItemResource::collection(
            $this->actions->index($request, $request->user())
        );
    }

    public function store(StoreAdminNotificationRequest $request): JsonResponse
    {
        return (new AdminNotificationResource(
            $this->actions->store($request->validated(), $request->user())
        ))->response()->setStatusCode(201);
    }

    public function show(Request $request, Notification $notification): AdminNotificationResource
    {
        return new AdminNotificationResource(
            $this->actions->show($notification, $request->user())
        );
    }

    public function update(UpdateAdminNotificationRequest $request, Notification $notification): AdminNotificationResource
    {
        return new AdminNotificationResource(
            $this->actions->update($notification, $request->validated(), $request->user())
        );
    }

    public function destroy(Request $request, Notification $notification): Response
    {
        $this->actions->destroy($notification, $request->user());

        return response()->noContent();
    }
}
