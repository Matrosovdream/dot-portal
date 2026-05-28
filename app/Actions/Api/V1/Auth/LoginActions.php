<?php

namespace App\Actions\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Services\Api\V1\Auth\AuthService;

class LoginActions
{
    public function __construct(protected AuthService $auth) {}

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $this->auth->startSession($request);

        return $request->user()->load('roles');
    }
}
