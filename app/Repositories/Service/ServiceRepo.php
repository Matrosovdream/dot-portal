<?php
namespace App\Repositories\Service;

use App\Repositories\AbstractRepo;
use App\Models\Service;


class ServiceRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new Service();
    }

    public function mapItem($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'description' => $item->description,
            'price' => $item->price,
            'Model' => $item
        ];
    }

}