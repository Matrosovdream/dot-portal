<?php
namespace App\Actions\Dashboard;

use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\User\UserPaymentHistoryRepo;

class SubscriptionUserActions {

    private $userRepo;
    private $userSubRepo;
    private $userPaymentHistoryRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->userSubRepo = new UserSubscriptionRepo();
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();
    }

    public function index()
    {

        $user_id = auth()->user()->id;

        $data = [
            'title' => 'My subscription',
            'user' => $this->userRepo->getByID( $user_id ),
            'subscription' => $this->userSubRepo->getByUserID( $user_id ),
            'paymentHistory' => $this->userPaymentHistoryRepo->getAll( ['user_id' => $user_id], 100 )
        ];

        return $data;
    }

}