<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserSubscription;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserSubscriptionPaymentRepo;



class UserSubscriptionRepo extends AbstractRepo
{

    protected $subscriptionRepo;
    protected $subscriptionPaymentRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserSubscription();

        $this->subscriptionRepo = new SubscriptionRepo();
        $this->subscriptionPaymentRepo = new UserSubscriptionPaymentRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'subscription' => $this->subscriptionRepo->mapItem( $item->subscription ),
            'price' => $item->price,
            'discount' => $item->discount,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'status' => $item->status,
            'isActive' => $item->isActive(),
            //'payments' => $this->subscriptionPaymentRepo->mapItems( $item->payments->all() ),
            'Model' => $item
        ];

        return $res;
    }

}