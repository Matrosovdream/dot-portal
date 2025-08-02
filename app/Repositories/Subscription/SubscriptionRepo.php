<?php
namespace App\Repositories\Subscription;

use App\Repositories\AbstractRepo;
use App\Models\Subscription;
use App\Repositories\Subscription\SubscriptionPointRepo;


class SubscriptionRepo extends AbstractRepo
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
            'slug' => $item->slug,
            'name' => $item->name,
            'price' => $item->price,
            'price_per_driver' => $item->price_per_driver ?? 0,
            'is_custom_price' => $item->is_custom_price ?? false,
            'drivers_amount_from' => $item->drivers_amount_from,
            'drivers_amount_to' => $item->drivers_amount_to,
            'discount' => $item->discount,
            'duration' => $item->duration,
            'short_description' => $item->short_description,
            'description' => $item->description,
            'points' => $this->pointsRepo->mapItems($item->points),
            'Model' => $item
        ];

        return $res;
    }

}