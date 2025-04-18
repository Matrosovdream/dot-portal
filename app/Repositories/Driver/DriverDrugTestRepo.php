<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverDrugTest;
use App\Repositories\File\FileRepo;

class DriverDrugTestRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    protected $fileRepo;

    public function __construct()
    {
        $this->model = new DriverDrugTest();

        $this->fileRepo = new FileRepo();

    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'driver_id' => $item->driver_id,
            'test_type' => $item->test_type,
            'test_date' => $item->test_date,
            'result' => $item->result,
            'file_id' => $item->file_id,
            'file' => $this->fileRepo->mapItem($item->file),
            'Model' => $item
        ];

        return $res;
    }

}