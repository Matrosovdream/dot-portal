<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserSubscriptionPayment;



class UserSubscriptionPaymentRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserSubscriptionPayment();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'user_subscription_id' => $item->user_subscription_id,
            'amount' => $item->amount,
            'payment_date' => $item->payment_date,
            'payment_method_id' => $item->payment_method_id,
            'transaction_id' => $item->transaction_id,
            'status' => $item->status,
            'notes' => $item->notes,
            'Model' => $item
        ];

        return $res;
    }

}