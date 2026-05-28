<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\PasswordActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\PasswordEmailRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function __construct(protected PasswordActions $actions) {}

    public function store(PasswordEmailRequest $request): JsonResponse
    {
        $status = $this->actions->sendResetLink($request->validated()['email']);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json([
            'message' => __($status),
            'errors'  => ['email' => [__($status)]],
        ], 422);
    }
}
