<?php

namespace App\Services\User;

use App\Contracts\Mail\MailServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginToken;
use Illuminate\Support\Str;

class UserService {

    public function __construct(
        protected MailServiceInterface $mailService
    ) {}

    public function activateAccount( $user )
    {

        if( !$user ) { return false; }

        $user->subscription()->update([
            'status' => 'active',
        ]);

        // Update user registration step and status
        $user->reg_step = null;
        $user->is_active = true;
        $user->save();

        return true;
    }

    public function sendWelcomeEmail( $user )
    {

        if( !$user ) { return false; }

        $template = 'welcome-company';
        $variables = [
            'firstname' => $user->firstname,
            'login_url' => route('login')
        ];

        $user->email = 'matrosovdream@gmail.com';

        // Send email using MailgunService
        return $this->mailService->sendTemplate(
            $user->email, 
            'Welcome to DOT Portal!',
            $template, 
            $variables
        );
    }

    public function sendApprovedRequestEmail( $user ) {

        if( !$user ) { return false; }

        $template = 'approved-drivers-request';
        $variables = [
            'firstname' => $user->firstname,
            'login_url' => route('login')
        ];

        // Send email using MailgunService
        return $this->mailService->sendTemplate(
            $user->email, 
            'Your request has been approved!',
            $template, 
            $variables
        );

    }

    public function makeLoginLink( $user ) {

        if( !$user ) { return false; }

        $token = $this->generateLoginToken($user);

        // Return the login link
        return route('login.onetime', ['token' => $token]);
    }

    public function generateLoginToken($user) {

        if( !$user ) { return false; }

        // Time 2 days in minutes
        $expiresAt = now()->addDays(2);

        // Generate a unique token
        $token = hash('sha256', Str::random(64));

        // Create a login token record
        $loginToken = new LoginToken([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
        $loginToken->save();

        return $token;

    }

    public function validateLoginToken( $token ) {

        $hashed = hash('sha256', $token);

        $record = LoginToken::where('token', $hashed)
            ->where('expires_at', '>', now())
            ->first();

        if( $record ) { 
            return $record;
        }

        return false; // Token is invalid or expired

    }

    public function removeCurrentUser() {

        if( auth()->check() ) {

            $user = Auth::user();

            // Delete user account
            $user->delete();

            // Log out the user
            Auth::logout();

            // Invalidate the session
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return true;

        }

    }

}