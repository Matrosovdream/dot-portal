<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\Service;


class ServiceRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new Service();
    }

    public function mapItem($item)
    {
        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'user_id' => $item->user_id,
            'Model' => $item
        ];
        return $res;
    }

}