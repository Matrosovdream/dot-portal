<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Api\V1\Auth\EmailVerificationActions;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(protected EmailVerificationActions $actions) {}

    public function status(Request $request): JsonResponse
    {
        return response()->json($this->actions->status($request));
    }

    public function send(Request $request): JsonResponse
    {
        $sent = $this->actions->send($request);
        return response()->json([
            'message' => $sent ? 'Verification link sent.' : 'Already verified.',
        ]);
    }

    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        return response()->json($this->actions->verify($request));
    }
}
