<?php
namespace App\Actions\Dashboard;

use App\Repositories\Vehicle\VehicleRepo;
use App\Mixins\File\FileStorage;
use App\Helpers\Validation\Models\VehicleValidation;
use App\Repositories\Vehicle\VehicleInspectionRepo;
use App\Repositories\Vehicle\VehicleDriverHistoryRepo;

class VehicleUserActions
{

    private $vehicleRepo;
    protected $fileStorage;
    protected $inspectionRepo;
    protected $vehicleValidation;
    protected $driverHistoryRepo;


    public function __construct()
    {
        $this->vehicleRepo = new VehicleRepo();
        $this->fileStorage = new FileStorage();
        $this->inspectionRepo = new VehicleInspectionRepo();
        $this->driverHistoryRepo = new VehicleDriverHistoryRepo();

        // Validation
        $this->vehicleValidation = new VehicleValidation();

    }

    public function index()
    {

        $vehicles = $this->vehicleRepo->getAll([], $paginate = 10);

        $data = [
            'title' => 'My vehicles',
            'vehicles' => $vehicles
        ];

        return $data;
    }

    public function show($vehicle_id)
    {
        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        return [
            'title' => 'Vehicle details',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'references' => $this->vehicleRepo->getReferences()
        ];

    }

    public function profile($vehicle_id)
    {
        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        return [
            'title' => 'Vehicle profile',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'references' => $this->vehicleRepo->getReferences()
        ];

    }

    public function updateProfile($vehicle_id, $request)
    {
        return [];
    }

    public function update($vehicle_id, $request)
    {
        $data = $this->vehicleRepo->update($vehicle_id, $request);

        // Save profile photo from request
        $this->saveProfilePhoto($vehicle_id);

        return $this->vehicleRepo->getByID($vehicle_id);
    }

    public function mvr($driver_id)
    {

        $vehicle = $this->vehicleRepo->getByID($driver_id);

        return [
            'title' => 'Vehicle MVR',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'references' => $this->vehicleRepo->getReferences()
        ];

    }

    public function updateMvr($vehicle_id, $request)
    {

        return $this->vehicleRepo->updateMvr(
            $vehicle_id,
            $request,
            $files = [
                'mvr' => "mvr_document"
            ]
        );

    }

    public function insurance($vehicle_id)
    {

        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Vehicle insurance',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'references' => $this->vehicleRepo->getReferences()
        ];

        return $data;
    }

    public function updateInsurance($vehicle_id, $request)
    {

        return $this->vehicleRepo->setInsurance(
            $vehicle_id,
            $request['insurance_id']
        );

    }

    public function inspections($vehicle_id)
    {

        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        return [
            'title' => 'Vehicle inspections',
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'vehicle' => $vehicle
        ];

    }

    public function storeInspection($vehicle_id, $request)
    {

        return $this->vehicleRepo->addInspection(
            $vehicle_id,
            $request,
            ['document' => 'document']
        );

    }

    public function updateInspection($inspection_id, $request)
    {

        return $this->vehicleRepo->updateInspection(
            $inspection_id,
            $request,
            ['document' => 'document']
        );

    }

    public function destroyInspection($inspection_id)
    {
        $this->inspectionRepo->delete($inspection_id);
    }

    public function driverHistory($vehicle_id)
    {

        $history = $this->driverHistoryRepo->getAll(
            ['vehicle_id' => $vehicle_id],
            $paged = 1000
        );

        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Driver history',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'history' => $history,
            'references' => $this->vehicleRepo->getReferences()
        ];

        return $data;
    }

    public function storeDriverHistory($vehicle_id, $request)
    {

        $request['vehicle_id'] = $vehicle_id;

        return $this->driverHistoryRepo->create(
            $request
        );

    }

    public function updateDriverHistory($drh_id, $request)
    {
        return $this->driverHistoryRepo->update(
            $drh_id,
            $request
        );
    }

    public function destroyDriverHistory($drh_id)
    {
        $this->driverHistoryRepo->delete($drh_id);
    }

    public function create()
    {
        return [
            'title' => 'Create vehicle',
            'references' => $this->vehicleRepo->getReferences()
        ];

    }

    public function store($request)
    {
        $request['company_id'] = auth()->user()->id;
        $data = $this->vehicleRepo->create($request);

        // Save profile photo from request
        $this->saveProfilePhoto($vehicle_id = $data['id']);

    }

    public function destroy($vehicle_id)
    {
        return $this->vehicleRepo->delete($vehicle_id);
    }

    public function saveProfilePhoto($vehicle_id)
    {
        // Driver document from request
        $file = $this->fileStorage->uploadFile(
            'profile_photo',
            'vehicle/' . $vehicle_id . '/profile',
            'local',
            ['tags' => ['profile photo']]
        );

        if ( isset($file['file']['id'])) {
            $this->vehicleRepo->update(
                $vehicle_id,
                ['profile_photo_id' => $file['file']['id']]
            );
        }

    }

}