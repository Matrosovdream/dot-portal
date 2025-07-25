<?php

namespace App\Actions\Auth;


class RegisterUserActions {

    public function __construct()
    {

    }

    public function index()
    {

        return [
            'title' => 'Register',
            'description' => 'Create a new account to access the DOT Portal.',
            'steps' => $this->getRegSteps(),
        ];

    }

    public function retrieveUsdot($request)
    {
        $usdot = $request->input('usdot');

        $dotApi = app('App\Helpers\TranspGov\TranspGovSnapshot');

        // Use the TranspGovSnapshot helper to retrieve USDOT information
        $snapshot = $dotApi->getByDot($usdot);

        if (!$snapshot) {
            return response()->json([]);
        }

        // to retrieve the USDOT information. For this example, we'll just return a dummy response.
        $response = [
            'usdot' => $usdot,
            'company_name' => $snapshot['legal_name'] ?? '',
            'trucks_number' => $snapshot['truck_units'] ?? 0,
            'drivers_number' => $snapshot['total_drivers'] ?? 0,
        ];

        return $response;
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