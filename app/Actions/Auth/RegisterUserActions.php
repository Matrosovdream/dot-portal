<?php

namespace App\Actions\Auth;


class RegisterUserActions {

    public function __construct()
    {

    }

    public function index()
    {


    }

    public function getRegSteps(): array
    {
        $steps = [
            'account' => [
                'number' => '1',
                'title' => 'Account Info',
                'slug' => 'account',
                'description' => 'Setup your account settings'
            ],
            'company' => [
                'number' => '2',
                'title' => 'Company Details',
                'slug' => 'company',
                'description' => 'Setup your Company details'
            ],
            'billing' => [
                'number' => '3',
                'title' => 'Billing Details',
                'slug' => 'billing',
                'description' => 'Provide your payment info'
            ],
            'payment' => [
                'number' => '4',
                'title' => 'Subscription payment',
                'slug' => 'payment',
                'description' => 'Payment for your subscription'
            ]
        ];
    
        // Set active based GET parameter 'step' and if empty then default to 'account'
        $activeStep = request()->get('step', 'account');    
        foreach( $steps as $key => $details ) {
            $steps[$key]['active'] = ($details['slug'] == $activeStep);
        }

        return $steps;
    }

}