<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserSubscription;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserSubscriptionPaymentRepo;
use App\Repositories\Driver\DriverRepo;



class UserSubscriptionRepo extends AbstractRepo
{

    protected $subscriptionRepo;
    protected $subscriptionPaymentRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];
    private $driverRepo;

    public function __construct()
    {
        $this->model = new UserSubscription();

        $this->subscriptionRepo = new SubscriptionRepo();
        $this->subscriptionPaymentRepo = new UserSubscriptionPaymentRepo();
        $this->driverRepo = new DriverRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $subscription = $this->subscriptionRepo->mapItem( $item->subscription ) ?? null;

        $res = [
            'id' => $item->id,
            'subscription' => $subscription, // Can be null
            'price' => $item->price,
            'price_per_driver' => $item->price_per_driver,
            'drivers_number' => $item->drivers_number,
            'discount' => $item->discount,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'status' => $item->status,
            'isActive' => $item->isActive(),
            //'payments' => $this->subscriptionPaymentRepo->mapItems( $item->payments->all() ),
        ];

        if( 
            $res['subscription'] &&
            isset($res['subscription']['drivers_amount']) &&
            $res['subscription']['drivers_amount'] > 0
            ) {
            // Drivers remained and used
            $res['driversUsed'] = $this->driverRepo->countDriversByCompany( auth()->user()->id );
            $res['driversRemained'] = $res['subscription']['drivers_amount'] - $res['driversUsed'];
        }

        $res['Model'] = $item;

        return $res;
    }

}