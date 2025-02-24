<?php
namespace App\Repositories\Subscription;

use App\Repositories\AbstractRepo;
use App\Models\Subscription;


class SubscriptionRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new Subscription();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'discount' => $item->discount,
            'duration' => $item->duration,
            'duration_type' => $item->duration_type,
            'duration_period' => $item->duration_period,
            'Model' => $item
        ];
        return $res;
    }

}