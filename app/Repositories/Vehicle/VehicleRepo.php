<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\Vehicle;
use App\Repositories\References\RefVehicleUnitTypeRepo;
use App\Repositories\References\RefVehicleOwnershipTypeRepo;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\File\FileRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\Vehicle\VehicleMvrRepo;


class VehicleRepo extends AbstractRepo
{

    protected $model;

    protected $fields = ['profilePhoto'];

    protected $refVehicleUnitTypeRepo;
    protected $refVehicleOwnershipTypeRepo;
    protected $driverRepo;
    protected $userRepo;
    protected $fileRepo;
    protected $mvrRepo;

    public function __construct()
    {
        $this->model = new Vehicle();

        $this->driverRepo = new DriverRepo();
        $this->userRepo = new UserRepo();

        $this->fileRepo = new FileRepo();
        $this->mvrRepo = new VehicleMvrRepo();

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

    public function getCompanyStats($company_id)
    {
        $itemsCount = $this->model
            ->where('company_id', $company_id)
            ->count();

        return [
            'total' => $itemsCount
        ];
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
            'company' => $this->userRepo->mapItem( $item->company ),
            'regExpireDate' => $item->reg_expire_date,
            'inspectionExpireDate' => $item->inspection_expire_date,
            'profilePhoto' => $this->fileRepo->mapItem( $item['profilePhoto'] ),
            'mvr' => $this->mvrRepo->mapItem( $item->mvr ),
            'Model' => $item
        ];
        return $res;
    }

}