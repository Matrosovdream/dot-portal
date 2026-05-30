<?php

namespace App\Http\Controllers\Api\V1\Admin\Users;

use App\Actions\Api\V1\Admin\Users\AdminUserActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\Users\StoreUserRequest;
use App\Http\Requests\Api\V1\Admin\Users\UpdateUserRequest;
use App\Http\Resources\V1\Admin\Users\AdminUserListItemResource;
use App\Http\Resources\V1\Admin\Users\AdminUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserController extends Controller
{
    public function __construct(
        protected AdminUserActions $actions,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return AdminUserListItemResource::collection(
            $this->actions->index($request)
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->actions->store($request);

        return (new AdminUserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, User $user): JsonResource
    {
        return new AdminUserResource(
            $this->actions->show($request, $user)
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResource
    {
        return new AdminUserResource(
            $this->actions->update($request, $user)
        );
    }

    public function destroy(Request $request, User $user): Response
    {
        $this->actions->destroy($request, $user);

        return response()->noContent();
    }
}
