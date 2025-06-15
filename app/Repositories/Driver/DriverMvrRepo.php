<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverMvr;
use App\Repositories\File\FileRepo;


class DriverMvrRepo extends AbstractRepo
{

    protected $model;

    protected $fileRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new DriverMvr();

        $this->fileRepo = new FileRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'driver_id' => $item->driver_id,
            'mvr_number' => $item->mvr_number,
            'mvr_date' => $item->mvr_date,
            'file_id' => $item->file_id,
            'file' => $this->fileRepo->mapItem($item->file),
            'Model' => $item

        ];
        return $res;
    }

}