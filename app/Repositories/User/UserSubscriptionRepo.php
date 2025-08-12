<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserSubscription;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserSubscriptionPaymentRepo;
use App\Repositories\Driver\DriverRepo;



class UserSubscriptionRepo extends AbstractRepo
{

    protected $userRepo;
    protected $subscriptionRepo;
    protected $subscriptionPaymentRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];
    private $driverRepo;

    public function __construct()
    {
        $this->model = new UserSubscription();

        $this->userRepo = new UserRepo();
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
            'user_id' => $item->user_id,
            'user' => $this->userRepo->mapItem($item->user),
            'subscription_id' => $item->subscription_id,
            'subscription' => $subscription, // Can be null
            'price' => $item->price,
            'price_per_driver' => $item->price_per_driver,
            'drivers_number' => $item->drivers_number,
            'discount' => $item->discount,
            'payment_card_id' => $item->payment_card_id,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'status' => $item->status,
            'isActive' => $item->isActive(),
            //'payments' => $this->subscriptionPaymentRepo->mapItems( $item->payments->all() ),
        ];

        // Drivers remained and used
        if( 
            $res['subscription']
            ) {

            $driversNumber = $item->drivers_number ?? 0;

            // Drivers remained and used
            $res['driversUsed'] = $this->driverRepo->countDriversByCompany( $item->user_id );
            $res['driversRemained'] = $driversNumber - $res['driversUsed'];
        }

        $res['Model'] = $item;

        return $res;
    }

}