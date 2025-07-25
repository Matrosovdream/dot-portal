<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {

        $data = [
            'title' => 'Register',
            'description' => 'Create a new account to access the DOT Portal.',
            'steps' => $this->getRegSteps(),
        ];

        return view('auth.register', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:15'],
            'usdot' => ['required', 'string', 'max:20'],
            'company_name' => ['required', 'string', 'max:255'],
            'trucks_number' => ['required', 'integer', 'min:1'],
            'drivers_number' => ['required', 'integer', 'min:1'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        // Prepare firstname and lastname
        $nameParts = explode(' ', $request->name, 2);
        $firstname = $nameParts[0];
        $lastname = isset($nameParts[1]) ? $nameParts[1] : '';


        // Create user
        $user = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create company
        $user->company()->create([
            'name' => $request->company_name,
            'phone' => $request->phone,
            'dot_number' => $request->usdot,
            //'trucks_number' => $request->trucks_number,
            //'drivers_number' => $request->drivers_number,
        ]);

        // Assign role to user
        $user->setRole('company');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard.home', absolute: false));
    }

    public function retrieveUsdot(Request $request)
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

        return response()->json($response);
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
