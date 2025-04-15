<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleDriverHistory;
use App\Repositories\Driver\DriverRepo;


class VehicleDriverHistoryRepo extends AbstractRepo
{

    protected $model;
    protected $fields = [];
    protected $driverRepo;

    public function __construct()
    {
        $this->model = new VehicleDriverHistory();

        $this->driverRepo = new DriverRepo;
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'vehicle_id' => $item->vehicle_id,
            'driver_id' => $item->driver_id,
            'driver' => $this->driverRepo->mapItem( $item->driver ),
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'Model' => $item,
        ]; 
        return $res;
    }

}