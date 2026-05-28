<?php

namespace App\Actions\Api\V1\Auth;

use App\Services\Api\V1\Auth\AuthService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordActions
{
    public function __construct(protected AuthService $auth) {}

    /** Send the password-reset link. Returns the broker status. */
    public function sendResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    /** Reset the password using the broker token. Returns the broker status. */
    public function reset(array $data): string
    {
        return Password::reset(
            $data,
            function ($user) use ($data) {
                $user->forceFill([
                    'password'       => Hash::make($data['password']),
                    'remember_token' => $this->auth->newRememberToken(),
                ])->save();

                event(new PasswordReset($user));
            },
        );
    }

    /** Update the authenticated user's password. */
    public function update(Request $request, string $password): void
    {
        $request->user()->update([
            'password' => Hash::make($password),
        ]);
    }
}
