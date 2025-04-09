<?php
namespace App\Repositories\Subscription;

use App\Repositories\AbstractRepo;
use App\Models\SubscriptionPoint;


class SubscriptionPointRepo extends AbstractRepo
{

    protected $model;

    protected $fields = ['points'];

    protected $pointsRepo;

    public function __construct()
    {
        $this->model = new SubscriptionPoint();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'subscription_id' => $item->subscription_id,
            'title' => $item->title,
            'description' => $item->description,
            'included' => $item->included,
            'Model' => $item
        ];
        return $res;
    }

}