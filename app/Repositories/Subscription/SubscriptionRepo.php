<?php
namespace App\Repositories\Subscription;

use App\Repositories\AbstractRepo;
use App\Models\Subscription;


class SubscriptionRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new Subscription();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'Model' => $item
        ];
        return $res;
    }

}