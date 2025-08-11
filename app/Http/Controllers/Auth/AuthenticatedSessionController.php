<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\User\UserService;


class AuthenticatedSessionController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) { 

    }


    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
  
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.home', absolute: false));

        /*
        if( Auth::user()->isAdmin() ) {
            return redirect()->intended(route('admin.home', absolute: false));
        } elseif( Auth::user()->isManager() ) {
            return redirect()->intended(route('manager.index', absolute: false));
        } else {
            return redirect()->intended(route('dashboard', absolute: false));
        }
        */

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle one-time login using a token.
     */
    public function loginOnce(string $token): RedirectResponse
    {
        $res = $this->userService->loginWithToken($token);

        if (!$res) {
            abort(404, 'Invalid or expired token.');
        }

        return redirect()->intended(route('dashboard.home', absolute: false));
    }


}
