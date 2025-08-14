<?php

namespace App\Services\User;

use App\Contracts\Mail\MailServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginToken;
use Illuminate\Support\Str;
use App\Models\User;

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

    public function sendOnceLoginLink( $user ) {

        if( !$user ) { return false; }

        // Generate the one-time login link
        $loginLink = $this->makeLoginLink($user->id);

        if( !$loginLink ) { return false; }

        $template = 'one-time-login';
        $variables = [
            'firstname' => $user->firstname,
            'login_link' => $loginLink
        ];

        // Send email using MailgunService
        $res = $this->mailService->sendTemplate(
            $user->email, 
            'Your one-time login link',
            $template, 
            $variables
        );

        return $res;

    }

    public function makeLoginLink( $user_id, $max_uses = 1 ) {

        $token = $this->generateLoginToken($user_id, $max_uses);

        // Return the login link
        return route('login.onetime', ['token' => $token]);
    }

    public function generateLoginToken($user_id, $max_uses = 1) {

        // Fetch the user by ID
        $user = User::find($user_id);   

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
            'max_uses' => $max_uses
        ]);
        $loginToken->save();

        return $token;

    }

    public function loginWithToken( $token ) {

        if( !$this->validateLoginToken($token) ) {
            return false; // Invalid or expired token
        }
        
        $loginToken = LoginToken::findByToken($token);
        if( !$loginToken ) { return false; }

        // Optionally, delete the token after successful login
        $loginToken->useToken();

        // Log in the user
        Auth::loginUsingId($loginToken->user_id);

        return true; // Login successful
    }

    public function validateLoginToken( $token ) {
        return (new LoginToken)->isValid( $token );
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