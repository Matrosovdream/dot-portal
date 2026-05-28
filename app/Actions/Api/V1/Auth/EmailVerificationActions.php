<?php

namespace App\Actions\Api\V1\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationActions
{
    public function status(Request $request): array
    {
        $user = $request->user();
        return [
            'verified' => $user->hasVerifiedEmail(),
            'email'    => $user->email,
        ];
    }

    public function send(Request $request): bool
    {
        if ($request->user()->hasVerifiedEmail()) {
            return false;
        }
        $request->user()->sendEmailVerificationNotification();
        return true;
    }

    public function verify(Request $request): array
    {
        $user = $request->user();
        if (!$user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        return ['verified' => true, 'email' => $user->email];
    }
}
