<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleMvr;


class VehicleMvrRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new VehicleMvr();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'vehicle_id' => $item->vehicle_id,
            'mvr_number' => $item->mvr_number,
            'mvr_date' => $item->mvr_date,
            'file_id' => $item->file_id,
            'Model' => $item

        ];
        return $res;
    }

}