<?php
namespace App\Repositories\Service;

use App\Repositories\AbstractRepo;
use App\Models\Service;
use App\Repositories\References\RefServiceGroupRepo;


class ServiceRepo extends AbstractRepo
{

    protected $ServiceGroupRepo;

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new Service();

        // References
        $this->ServiceGroupRepo = new RefServiceGroupRepo();

    }

    public function beforeCreate( $data ) {

        $data['status_id'] = 1;
        return $data;

    }

    public function mapItem($item)
    {

        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'description' => $item->description,
            'price' => $item->price,
            'group' => $this->ServiceGroupRepo->mapItem( $item->group ),
            'Model' => $item
        ];
    }

}