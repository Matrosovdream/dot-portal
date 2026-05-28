<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\LoginActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\V1\Auth\UserResource;

class LoginController extends Controller
{
    public function __construct(protected LoginActions $actions) {}

    public function store(LoginRequest $request)
    {
        return new UserResource($this->actions->login($request));
    }
}
