<?php
namespace App\Actions\Dashboard;

use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\User\UserPaymentHistoryRepo;

class SubscriptionUserActions {

    private $userRepo;
    private $userSubRepo;
    private $subRepo;
    private $userPaymentHistoryRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->userSubRepo = new UserSubscriptionRepo();
        $this->subRepo = new SubscriptionRepo();
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();
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

}