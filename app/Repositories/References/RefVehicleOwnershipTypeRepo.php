<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefVehicleOwnershipType;


class RefVehicleOwnershipTypeRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new RefVehicleOwnershipType;
    }

    public function mapItem($item)
    {
        if( empty($item) ) {
            return null;
        }
        
        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'code' => $item->code,
            'Model' => $item
        ];
        return $res;
    }

}