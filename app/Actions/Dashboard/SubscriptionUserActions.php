<?php
namespace App\Actions\Dashboard;

use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\User\UserPaymentHistoryRepo;
use App\Repositories\User\UserPaymentCardRepo;

class SubscriptionUserActions {

    private $userRepo;
    private $userSubRepo;
    private $subRepo;
    private $userPaymentHistoryRepo;
    private $userCardRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->userSubRepo = new UserSubscriptionRepo();
        $this->subRepo = new SubscriptionRepo();
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();
        $this->userCardRepo = new UserPaymentCardRepo();
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

        // Calculate percent used drivers
        $data['subscription']['driversUsedPercent'] = round( $data['subscription']['driversUsed'] / $data['subscription']['subscription']['drivers_amount'] * 100, 2 );

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
dd($request);
        $user_id = auth()->user()->id;

        $this->userCardRepo->create([
            'user_id' => $user_id,
            'card_name' => $request['card_name'],
            'card_number' => $request['card_number'],
            'expiration_date' => $request['expiration_date'],
            'cvv' => $request['cvv'],
        ]);

    }





}