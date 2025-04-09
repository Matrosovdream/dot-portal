<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserPaymentCard;
use App\Repositories\References\RefCountryStateRepo;



class UserPaymentCardRepo extends AbstractRepo
{

    protected $countryStateRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserPaymentCard();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'card_number' => $item->card_number,
            'cardholder_name' => $item->card_holder_name,
            'expiry_date' => $item->expiry_date,
            'payment_method_id' => $item->payment_method_id,
            'primary' => $item->primary,
            'Model' => $item
        ];

        return $res;
    }

}