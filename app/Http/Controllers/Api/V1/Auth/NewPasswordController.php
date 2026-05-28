<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\PasswordActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\PasswordResetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class NewPasswordController extends Controller
{
    public function __construct(protected PasswordActions $actions) {}

    public function store(PasswordResetRequest $request): JsonResponse
    {
        $status = $this->actions->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json([
            'message' => __($status),
            'errors'  => ['email' => [__($status)]],
        ], 422);
    }
}
