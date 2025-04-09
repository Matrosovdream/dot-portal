<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleInspection;
use App\Repositories\File\FileRepo;


class VehicleInspectionRepo extends AbstractRepo
{

    protected $model;

    protected $fileRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new VehicleInspection();

        $this->fileRepo = new FileRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'vehicle_id' => $item->vehicle_id,
            'inspection_date' => $item->inspection_date,
            'inspection_number' => $item->inspection_number,
            'file' => $this->fileRepo->mapItem($item->file),
            'Model' => $item,
        ];
        return $res;
    }

}