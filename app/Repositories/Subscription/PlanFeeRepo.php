<?php
namespace App\Repositories\Subscription;

use App\Repositories\AbstractRepo;
use App\Models\Subscription;
use App\Repositories\Subscription\SubscriptionPointRepo;


class PlanFeeRepo extends AbstractRepo
{

    protected $model;

    protected $fields = ['points'];

    protected $pointsRepo;

    public function __construct()
    {
        $this->model = new Subscription();

        $this->pointsRepo = new SubscriptionPointRepo();
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
            'short_description' => $item->short_description,
            'description' => $item->description,
            'drivers_amount' => $item->drivers_amount,
            'points' => $this->pointsRepo->mapItems($item->points),
            'Model' => $item
        ];

        return $res;
    }

}