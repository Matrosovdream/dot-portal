<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Repositories\Subscription\PlanFeeRepo;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\Subscription\SubscriptionRequestRepo;
use App\Services\Payments\PaymentCardService;
use App\Services\User\UserService;
use App\Contracts\Saferweb\SaferwebInterface;
use App\Contracts\Payment\PaymentInterface;


class RegisterUserActions {

    public function __construct(
        protected PlanFeeRepo $feeRepo,
        protected SubscriptionRepo $subRepo,
        protected SubscriptionRequestRepo $subRequestRepo,
        protected PaymentCardService $cardService,
        protected PaymentInterface $paymentService,
        protected UserService $userService,
        protected SaferwebInterface $saferweb
    )
    {}

    public function index(): array
    {

        $feePrice = $this->getFeePrice();

        if( auth()->check() ) {
            $sub = auth()->user()->subscription ?? null;
            
            if( $sub ) {
                $total_price = $sub->price + $feePrice;
            }
        }

        $subRequest = auth()->check() ? auth()->user()->subRequest : null;

        return [
            'title' => 'Register',
            'description' => 'Create a new account to access the DOT Portal.',
            'subscription' => $sub ?? null,
            'total_price' => $total_price ?? 0,
            'fee_price' => $feePrice,
            'subs' => $this->subRepo->getAll(),
            'subRequest' => $subRequest,
            'steps' => $this->getRegSteps(),
        ];

    }

    public function registerRemove() {

        $this->userService->removeCurrentUser();

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

        if( $request->has('save_card') ) {
            $paymentRes = $this->storePaymentSavedCard($request);
        } else {
            $paymentRes = $this->storePaymentNotSave($request);
        }

        if( $paymentRes['success'] ) {

            // Activate the subscription
            $this->userService->activateAccount( auth()->user() );

            return [
                'result' => true,
                'next_page' => route('dashboard.home'),
            ];

        }

    }    

    private function storePaymentNotSave( $request ) {

        $user = auth()->user();

        $cardData = $request->only([
            'card_name',
            'first_name',
            'last_name',
            'card_number',
            'card_expiry_month',
            'card_expiry_year',
            'card_cvv'
        ]);

        $paymentRes = $this->paymentService->chargeCustomerWithCard(
            $user->id,
            $this->getTotalPrice(),
            'USD',
            null,
            $cardData
        );

        return $paymentRes;

    }    

    private function storePaymentSavedCard($request) {

        $user = auth()->user();

        $primaryCard = $this->cardService->getUserPrimaryCard( $user->id );

        if( !$primaryCard ) {
            
            $cardData = $request->only([
                'card_name',
                'first_name',
                'last_name',
                'card_number',
                'card_expiry_month',
                'card_expiry_year',
                'card_cvv'
            ]);
            $this->cardService->storeCard($cardData);

            $primaryCard = $this->cardService->getUserPrimaryCard( $user->id );
            
        }

        if( $primaryCard ) {
            
            $paymentRes = $this->paymentService->chargeCustomerWithProfile(
                $user->id,
                $this->getTotalPrice()
            );

        }

        return $paymentRes;

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
            'reg_step' => 'company',
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
        $user->reg_step = 'payment';
        $user->save();

        // If custom request is made
        if( 
            $request->has('is_custom_request') && 
            $request->is_custom_request == 'true' 
            ) {
            $res = $this->storeCustomRequest( $request );
        }

        return [
            'result' => true,
            'next_page' => route('register', ['step' => 'payment'])
        ];
    }

    private function storeCustomRequest( $request )
    {
        $request->validate([
            'request_details' => ['required', 'string', 'max:10000'],
        ]);

        $user = auth()->user()->refresh();

        $this->subRequestRepo->sync([
            'user_id' => auth()->id(),
            'subscription_id' => $request->subscription_id ?? null,
            'user_subscription_id' => $user->subscription->id ?? null,
            'request_details' => $request->request_details,
            'status_id' => 1, // Pending status
        ]); 

        return true;
    }

    public function retrieveUsdot( $request ): array
    {
        return $this->saferweb->retrieveUsdotData(
            $request->input('usdot')
        );
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

    private function getFeePrice() {
        return $this->feeRepo->getPrimary()['price'] ?? 0;
    }

    private function getTotalPrice() {
        $feePrice = $this->getFeePrice();
        $subscription = auth()->user()->subscription ?? null;

        if( $subscription ) {
            return $subscription->price + $feePrice;
        }

        return $feePrice;
    }

}