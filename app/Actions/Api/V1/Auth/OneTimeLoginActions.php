<?php

namespace App\Actions\Api\V1\Auth;

use App\Services\User\UserService;
use App\Services\Api\V1\Auth\AuthService;
use Illuminate\Http\Request;

class OneTimeLoginActions
{
    public function __construct(
        protected UserService $userService,
        protected AuthService $auth,
    ) {}

    /** Returns the logged-in user (with roles), or null on bad/expired token. */
    public function loginByToken(Request $request, string $token)
    {
        if (!$this->userService->loginWithToken($token)) {
            return null;
        }

        $this->auth->startSession($request);

        return auth()->user()->load('roles');
    }
}
