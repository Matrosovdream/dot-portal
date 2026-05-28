<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\RegisterActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterAccountRequest;
use App\Http\Resources\V1\Auth\UserResource;

class RegisterController extends Controller
{
    public function __construct(protected RegisterActions $actions) {}

    public function store(RegisterAccountRequest $request)
    {
        $user = $this->actions->register($request->validated(), $request);

        return (new UserResource($user))->response()->setStatusCode(201);
    }
}
