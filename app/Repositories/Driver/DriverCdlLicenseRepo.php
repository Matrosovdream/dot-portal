<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverCdlLicense;
use App\Repositories\File\FileRepo;


class DriverCdlLicenseRepo extends AbstractRepo
{

    public $model;

    protected $fileRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new DriverCdlLicense();

        $this->fileRepo = new FileRepo();
    }

    public function getByDriverId($driverId)
    {
        $item = $this->model->where('driver_id', $driverId)->first();
        return $this->mapItem($item);
    }

    public function removeDocument($license_id)
    {

        $license = $this->getById($license_id);
        $file = $license['file'] ?? null;
        if( empty($file) ) {
            return;
        }
    
        // Remove from model
        $license['Model']->file_id = null;
        $license['Model']->save();

        // Remove from file repo
        $this->fileRepo->delete($file['id']);

    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'driver_id' => $item->driver_id,
            'license_number' => $item->license_number,
            'expiration_date' => $item->expiration_date,
            'file_id' => $item->file_id,
            'file' => $this->fileRepo->mapItem($item->file),
            'Model' => $item

        ];
        return $res;
    }

}