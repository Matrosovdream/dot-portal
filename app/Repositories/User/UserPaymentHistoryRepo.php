<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserPaymentHistory;
use App\Repositories\References\RefCountryStateRepo;



class UserPaymentHistoryRepo extends AbstractRepo
{

    protected $countryStateRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserPaymentHistory();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'user_id' => $item->user_id,
            'payment_method_id' => $item->payment_method_id,
            'subscription_id' => $item->subscription_id,
            'type' => $item->type,
            'amount' => $item->amount,
            'payment_date' => $item->payment_date,
            'transaction_id' => $item->transaction_id,
            'status' => $item->status,
            'notes ' => $item->notes,
            'Model' => $item
        ];

        return $res;
    }

}