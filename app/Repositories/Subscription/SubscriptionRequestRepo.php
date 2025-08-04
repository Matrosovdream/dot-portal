<?php
namespace App\Repositories\Subscription;

use App\Repositories\AbstractRepo;
use App\Models\SubscriptionCustomRequest;
use App\Repositories\User\UserRepo;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserSubscriptionRepo;



class SubscriptionRequestRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    protected $userRepo;
    protected $subRepo;
    protected $userSubRepo;

    public function __construct()
    {
        $this->model = new SubscriptionCustomRequest();

        $this->userRepo = app()->make(UserRepo::class);
        $this->subRepo = app()->make(SubscriptionRepo::class);
        $this->userSubRepo = app()->make(UserSubscriptionRepo::class);

    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'user' => $this->userRepo->mapItem($item->user),
            'subscription_id' => $item->subscription_id,
            'subscription' => $this->subRepo->mapItem($item->subscription),
            'user_subscription_id' => $item->user_subscription_id,
            'userSubscription' => $this->userSubRepo->mapItem($item->userSubscription),
            'request_details' => $item->request_details,
            'status_id' => $item->status_id,
            'Model' => $item
        ];

        return $res;
    }

}