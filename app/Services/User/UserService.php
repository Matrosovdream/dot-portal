<?php

namespace App\Services\User;

use App\Contracts\Mail\MailServiceInterface;
use Illuminate\Support\Facades\Auth;

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