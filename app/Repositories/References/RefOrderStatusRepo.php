<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefOrderStatus;


class RefOrderStatusRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new RefOrderStatus();
    }

    public function mapItem($item)
    {
        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'code' => $item->code,
            'description' => $item->description,
        ];
        return $res;
    }

}