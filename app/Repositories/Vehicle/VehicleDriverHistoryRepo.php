<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleDriverHistory;
use App\Repositories\File\FileRepo;


class VehicleDriverHistoryRepo extends AbstractRepo
{

    protected $model;
    protected $fields = [];

    public function __construct()
    {
        $this->model = new VehicleDriverHistory();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'vehicle_id' => $item->vehicle_id,
            'driver_id' => $item->driver_id,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'Model' => $item,
        ];
        return $res;
    }

}