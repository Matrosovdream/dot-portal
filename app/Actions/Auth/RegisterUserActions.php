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

        if( auth()->check() ) {
            $sub = auth()->user()->subscription ?? null;
            $firstPayment = 199;

            if( $sub ) {
                $total_price = $sub->price + $firstPayment;
            }
        }

        return [
            'title' => 'Register',
            'description' => 'Create a new account to access the DOT Portal.',
            'subscription' => $sub,
            'total_price' => $total_price ?? 0,
            'steps' => $this->getRegSteps(),
        ];

    }

    public function registerRemove() {

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
 
    public function store($request): array
    {

        $step = $request->input('step', null);

        switch ($step) {
            case 'account':
                $res = $this->storeAccount( $request );
                break;
            case 'company':
                $res = $this->storeCompany( $request );
                break;
            case 'payment':
                $res = $this->storePayment( $request );
                break;
            default:
                // Default action or error handling
                return [
                    'result' => false,
                ];
        }

        return $res;

    }

    private function storePayment($request)
    {

        $request->validate([
            'card_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'max:20'],
            'card_expiry_month' => ['required', 'string', 'max:2'],
            'card_expiry_year' => ['required', 'string', 'max:4'],
            'card_cvv' => ['required', 'string', 'max:4'],
        ]);

        dd($request->all());

        // Update user registration step
        $user->reg_step = 'payment';
        $user->save();

    }    

    private function storeAccount($request)
    {

        if( auth()->check() ) {
            $this->storeAccountAuthorized( $request );
        } else {
            $this->storeAccountGuest( $request );
        }

        return [
            'result' => true,
            'next_page' => route('register', ['step' => 'company'])
        ];

    }

    private function storeAccountAuthorized( $request ) {

        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
        ]);

        $user = auth()->user();

        // User update
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
        ]);

    }

    private function storeAccountGuest( $request ) {

        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        // Create user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => false,
            'reg_step' => 'account',
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

        return $user;

    }

    private function storeCompany($request)
    {

        $request->validate([
            'usdot' => ['required', 'string', 'max:20'],
            'company_name' => ['required', 'string', 'max:255'],
            'trucks_number' => ['required', 'integer', 'min:1'],
            'drivers_number' => ['required', 'integer', 'min:1'],
        ]);

        // Update company details
        $user = auth()->user();
        $companyData = [
            'name' => $request->company_name,
            'dot_number' => $request->usdot,
            'trucks_number' => $request->trucks_number,
            'drivers_number' => $request->drivers_number,
        ];

        // Update/Create company
        if( isset( $user->company ) ) {
            $user->company()->update($companyData);
        } else {
            $user->company()->create($companyData);
        }

        // Update subscription
        $user->subscription()->update([
            'subscription_id' => $request->subscription_id ?? null,
            'price' => $request->subscription_price ?? 0,
            'price_per_driver' => $request->price_per_driver ?? 0,
            'drivers_number' => $request->drivers_number ?? 0,
        ]);

        // Update user registration step
        $user->reg_step = 'company';
        $user->save();

        return [
            'result' => true,
            'next_page' => route('register', ['step' => 'payment'])
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
            /*'billing' => [
                'number' => '3',
                'title' => 'Billing Details',
                'slug' => 'billing',
                'description' => 'Provide your payment info'
            ],*/
            'payment' => [
                'number' => '3',
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