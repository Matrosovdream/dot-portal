<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\OneTimeLoginActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Auth\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OneTimeLoginController extends Controller
{
    public function __construct(protected OneTimeLoginActions $actions) {}

    public function show(Request $request, string $token): JsonResponse
    {
        $user = $this->actions->loginByToken($request, $token);

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired token.'], 404);
        }

        return (new UserResource($user))->response();
    }
}
