<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverCdlLicense;
use App\Repositories\File\FileRepo;


class DriverCdlLicenseRepo extends AbstractRepo
{

    protected $model;

    protected $fileRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new DriverCdlLicense();

        $this->fileRepo = new FileRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'driver_id' => $item->driver_id,
            'license_number' => $item->mvr_number,
            'expiration_date' => $item->mvr_date,
            'file_id' => $item->file_id,
            'file' => $this->fileRepo->mapItem($item->file),
            'Model' => $item

        ];
        return $res;
    }

}