<?php

namespace App\Actions\Api\V1\Auth;

use App\Services\Api\V1\Auth\AuthService;
use Illuminate\Http\Request;

class LogoutActions
{
    public function __construct(protected AuthService $auth) {}

    public function logout(Request $request): void
    {
        $this->auth->logout($request);
    }
}
