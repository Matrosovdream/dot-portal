<?php

namespace App\Actions\Api\V1\Auth;

use App\Services\Api\V1\Auth\AuthService;
use Illuminate\Http\Request;

class RegisterActions
{
    public function __construct(protected AuthService $auth) {}

    public function register(array $data, Request $request)
    {
        return $this->auth->register($data, $request);
    }
}
