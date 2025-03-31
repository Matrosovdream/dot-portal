<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\Vehicle;
use App\Repositories\References\RefVehicleUnitTypeRepo;
use App\Repositories\References\RefVehicleOwnershipTypeRepo;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\File\FileRepo;


class VehicleRepo extends AbstractRepo
{

    protected $model;

    protected $fields = ['profilePhoto'];

    protected $refVehicleUnitTypeRepo;
    protected $refVehicleOwnershipTypeRepo;
    protected $driverRepo;
    protected $fileRepo;

    public function __construct()
    {
        $this->model = new Vehicle();

        $this->driverRepo = new DriverRepo();

        $this->fileRepo = new FileRepo();

        // References
        $this->refVehicleUnitTypeRepo = new RefVehicleUnitTypeRepo();
        $this->refVehicleOwnershipTypeRepo = new RefVehicleOwnershipTypeRepo();

    }

    public function getReferences()
    {
        $references = [
            'unitType' => $this->refVehicleUnitTypeRepo->getAll([], $paginate=1000),
            'ownershipType' => $this->refVehicleOwnershipTypeRepo->getAll([], $paginate=1000),
            'driver' => $this->driverRepo->getAll([], $paginate=1000),
        ];

        return $references;
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'unitType' => $this->refVehicleUnitTypeRepo->mapItem( $item->unitType ),
            'number' => $item->number,
            'vin' => $item->vin,
            'ownershipType' => $this->refVehicleOwnershipTypeRepo->mapItem( $item->ownershipType ),
            'driver' => $this->driverRepo->mapItem( $item->driver ),
            'regExpireDate' => $item->reg_expire_date,
            'inspectionExpireDate' => $item->inspection_expire_date,
            'profilePhoto' => $this->fileRepo->mapItem( $item['profilePhoto'] ),
            'Model' => $item
        ];
        return $res;
    }

}