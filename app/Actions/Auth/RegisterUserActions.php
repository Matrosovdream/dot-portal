<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class RegisterUserActions {

    public function __construct()
    {

    }

    public function index(): array
    {

        return [
            'title' => 'Register',
            'description' => 'Create a new account to access the DOT Portal.',
            'steps' => $this->getRegSteps(),
        ];

    }

    public function store($request): array
    {

        $step = $request->input('step', null);

        switch ($step) {
            case 'account':
                $res = $this->storeAccount( $request );
                break;
            case 'company':
                // Handle company details logic
                break;
            case 'billing':
                // Handle billing details logic
                break;
            case 'payment':
                // Handle payment logic
                break;
            default:
                // Default action or error handling
                return [
                    'result' => false,
                ];
        }

        return $res;

    }

    private function storeAccount($request)
    {

        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:15'],
            //'usdot' => ['required', 'string', 'max:20'],
            //'company_name' => ['required', 'string', 'max:255'],
            //'trucks_number' => ['required', 'integer', 'min:1'],
            // 'drivers_number' => ['required', 'integer', 'min:1'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        // Create user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role to user
        $user->setRole('company');

        // Create company
        $user->company()->create([
            'name' => '',
            'phone' => $request->phone,
            'dot_number' => '',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return [
            'result' => true,
            'next_page' => route('register', ['step' => 'company'])
        ];

    }

    public function retrieveUsdot($request): array
    {
        $usdot = $request->input('usdot');

        $dotApi = app('App\Helpers\TranspGov\TranspGovSnapshot');

        // Use the TranspGovSnapshot helper to retrieve USDOT information
        $snapshot = $dotApi->getByDot($usdot);

        if (!$snapshot) {
            return [];
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