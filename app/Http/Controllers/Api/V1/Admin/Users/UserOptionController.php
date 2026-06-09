<?php

namespace App\Http\Controllers\Api\V1\Admin\Users;

use App\Actions\Api\V1\Admin\Users\AdminUserActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\Users\UserOptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Lightweight user lookup for the Operations owner-filter picker.
 *
 * Available to admin AND manager (unlike the admin-only /admin/users CRUD).
 * Reuses AdminUserActions::index for the q firstname/lastname/fullname/email
 * search + pagination, returning the minimal UserOptionResource.
 */
class UserOptionController extends Controller
{
    public function __construct(
        protected AdminUserActions $actions,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return UserOptionResource::collection(
            $this->actions->index($request)
        );
    }
}
