<?php

namespace App\Services\Api\V1\Auth;

use App\Repositories\User\UserRepo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(protected UserRepo $userRepo) {}

    /**
     * Persist a new user with the given role and start an authenticated session.
     * Returns the fresh User model (eager-loaded with roles).
     */
    public function register(array $data, Request $request)
    {
        $user = $this->userRepo->getModel()::create([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'is_active' => false,
            'reg_step'  => 'company',
            'password'  => Hash::make($data['password']),
        ]);

        $user->setRole($data['role'] ?? 'company');

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return $user->fresh()->load('roles');
    }

    /**
     * Regenerate the session after a successful authentication.
     */
    public function startSession(Request $request): void
    {
        $request->session()->regenerate();
    }

    /**
     * Tear down the authenticated session.
     */
    public function logout(Request $request): void
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * Generate a fresh remember-token hash; used by password reset.
     */
    public function newRememberToken(): string
    {
        return Str::random(60);
    }
}
