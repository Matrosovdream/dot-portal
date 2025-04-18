<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleMvr;
use App\Repositories\File\FileRepo;


class VehicleMvrRepo extends AbstractRepo
{

    protected $model;

    protected $fileRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new VehicleMvr();

        $this->fileRepo = new FileRepo();
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
            'file' => $this->fileRepo->mapItem($item->file),
            'Model' => $item

        ];
        return $res;
    }

}