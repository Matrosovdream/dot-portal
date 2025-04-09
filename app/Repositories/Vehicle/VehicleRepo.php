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
use App\Mixins\File\FileStorage;
use App\Repositories\Insurance\InsuranceVehicleRepo;
use App\Repositories\Vehicle\VehicleInspectionRepo;


class VehicleRepo extends AbstractRepo
{

    protected $model;

    protected $fields = ['profilePhoto', 'unitType', 'ownershipType', 'driver', 'company', 'mvr', 'inspections'];

    protected $refVehicleUnitTypeRepo;
    protected $refVehicleOwnershipTypeRepo;
    protected $driverRepo;
    protected $userRepo;
    protected $fileRepo;
    protected $mvrRepo;
    protected $fileStorage;
    protected $insuranceRepo;
    protected $inspectionRepo;
    protected $driverHistoryRepo;

    public function __construct()
    {
        $this->model = new Vehicle();

        $this->driverRepo = new DriverRepo();
        $this->userRepo = new UserRepo();

        $this->fileRepo = new FileRepo();
        $this->mvrRepo = new VehicleMvrRepo();
        $this->inspectionRepo = new VehicleInspectionRepo();
        $this->driverHistoryRepo = new VehicleDriverHistoryRepo();

        // References
        $this->refVehicleUnitTypeRepo = new RefVehicleUnitTypeRepo();
        $this->refVehicleOwnershipTypeRepo = new RefVehicleOwnershipTypeRepo();
        $this->insuranceRepo = new InsuranceVehicleRepo();

        $this->fileStorage = new FileStorage();

    }

    public function getReferences()
    {
        $references = [
            'unitType' => $this->refVehicleUnitTypeRepo->getAll([], $paginate=1000),
            'ownershipType' => $this->refVehicleOwnershipTypeRepo->getAll([], $paginate=1000),
            'driver' => $this->driverRepo->getAll([], $paginate=1000),
            'insurance' => $this->insuranceRepo->getAll(['company_id' => auth()->user()->id], $paginate=1000),
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

    public function updateMvr($vehicle_id, $request, $files=[])
    {

        $vehicle = $this->getByID($vehicle_id);

        if( isset($vehicle['mvr']) ) {
            $this->mvrRepo->update( $vehicle['mvr']['id'], $request );
        } else {
            $request['vehicle_id'] = $vehicle_id;
            $this->mvrRepo->create( $request );
        }

        $vehicle = $this->getByID($vehicle_id);

        // Upload files
        if( isset($files['mvr']) ) {
            
            $tags = ['Vehicle MVR', 'Vehicle MVR #' . $vehicle_id];

            $file = $this->fileStorage->uploadFile(
                $files['mvr'], 
                'vehicles/' . $vehicle_id . '/mvr',
                'local',
                ['tags' => $tags]
            );

            if( isset($file['file']['id']) ) {
                $this->mvrRepo->update( $vehicle['mvr']['id'], ['file_id' => $file['file']['id']]);
            }

        }

        return $vehicle['mvr'];

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
            'inspections' => $this->inspectionRepo->mapItems( $item->inspections ),
            'driverHistory' => $this->driverHistoryRepo->mapItems( $item->driverHistory ),
            'Model' => $item
        ];

        return $res;
    }

}