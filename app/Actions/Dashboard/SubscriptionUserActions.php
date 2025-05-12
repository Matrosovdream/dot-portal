<?php
namespace App\Actions\Dashboard;

use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\User\UserPaymentHistoryRepo;
use App\Repositories\User\UserPaymentCardRepo;
use App\Mixins\Gateways\AuthnetGateway;

class SubscriptionUserActions {

    private $userRepo;
    private $userSubRepo;
    private $subRepo;
    private $userPaymentHistoryRepo;
    private $userCardRepo;
    private $authnet;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->userSubRepo = new UserSubscriptionRepo();
        $this->subRepo = new SubscriptionRepo();
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();
        $this->userCardRepo = new UserPaymentCardRepo();
        $this->authnet = new AuthnetGateway;
    }

    public function index()
    {

        $user_id = auth()->user()->id;

        $data = [
            'title' => 'My subscription',
            'user' => $this->userRepo->getByID( $user_id ),
            'subscription' => $this->userSubRepo->getByUserID( $user_id ),
            'allSubscriptions' => $this->subRepo->getAll( [], 100 ),
            'paymentHistory' => $this->userPaymentHistoryRepo->getAll( ['user_id' => $user_id], 100 )
        ];

        // Calculate percent of used drivers
        $data['subscription']['driversUsedPercent'] = round( $data['subscription']['driversUsed'] / $data['subscription']['subscription']['drivers_amount'] * 100, 2 );


        // Test
        //$this->testCard();

        return $data;
    }

    public function update($request)
    {

        $user_id = auth()->user()->id;
        $userSubscription = $this->userSubRepo->getByUserID( $user_id );

        $this->userSubRepo->update( 
            $userSubscription['id'], 
            ['subscription_id' => $request['plan']]
        );

    }

    public function destroyCard( $card_id ) {

        return $this->userCardRepo->delete( $card_id );

    }

    public function storeCard( $request ) {

        $user_id = auth()->user()->id;

        // Split the expiration date into month and year
        $expireDate = $request['card_expiry_month'] . '/' . $request['card_expiry_year'];

        $this->userCardRepo->create([
            'user_id' => $user_id,
            'card_holder_name' => $request['card_name'],
            'card_number' => $request['card_number'],
            'expiry_date' => $expireDate,
            'cvv' => $request['card_cvv'],
        ]);

    }

    public function testCard() {

        $card = [
            'number' => '4111111111111111',
            'expiry' => '2026-12',
            'cvv' => '123',
            'first_name' => '',
            'last_name' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
        ];
        $email = 'user22@example.com';
    

        $profile = $this->authnet->createCustomerPaymentProfile($card, $email);
        dd($profile);
        
        // Charge the card once
        $transactionId = $this->authnet->chargeCustomerProfile(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            49.99
        );
        

        // Subscribe user
        $subscriptionId = $this->authnet->createSubscription(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            9.99
        );

        

    }

}