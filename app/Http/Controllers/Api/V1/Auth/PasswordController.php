<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\PasswordActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\PasswordUpdateRequest;

class PasswordController extends Controller
{
    public function __construct(protected PasswordActions $actions) {}

    public function update(PasswordUpdateRequest $request)
    {
        $this->actions->update($request, $request->validated()['password']);
        return response()->noContent();
    }
}
