<?php
namespace App\Actions\Dashboard;

use App\Repositories\Vehicle\VehicleRepo;
use App\Mixins\File\FileStorage;
use App\Helpers\Validation\Models\VehicleValidation;

class VehicleUserActions {

    private $vehicleRepo;
    protected $fileStorage;
    protected $vehicleValidation;


    public function __construct()
    {
        $this->vehicleRepo = new VehicleRepo();
        $this->fileStorage = new FileStorage();

        // Validation
        $this->vehicleValidation = new VehicleValidation();

    }

    public function index()
    {

        $vehicles = $this->vehicleRepo->getAll( [], $paginate = 10 );

        $data = [
            'title' => 'My vehicles',
            'vehicles' => $vehicles
        ];

        return $data;
    }

    public function show($vehicle_id)
    {
        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Vehicle details',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'references' => $this->vehicleRepo->getReferences()
        ];

        return $data;
    }

    public function profile( $vehicle_id )
    {
        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Vehicle profile',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'references' => $this->vehicleRepo->getReferences()
        ];

        return $data;
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

        return $data;
    }

    public function mvr($driver_id) {

        $vehicle = $this->vehicleRepo->getByID($driver_id);

        $data = [
            'title' => 'Vehicle MVR',
            'vehicle' => $vehicle,
            'validation' => $this->vehicleValidation->setData($vehicle)->validateAll(),
            'references' => $this->vehicleRepo->getReferences()
        ];

        return $data;

    }

    public function updateMvr($vehicle_id, $request) {

        return $this->vehicleRepo->updateMvr(
            $vehicle_id, 
            $request,
            $files = [
                'mvr' => "mvr_document"
            ]
        );

    }

    public function insurance($vehicle_id) {

        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Vehicle insurance',
            'vehicle' => $vehicle,
            'references' => $this->vehicleRepo->getReferences()
        ];

        return $data;
    }

    public function updateInsurance($vehicle_id, $request) {

        return $this->vehicleRepo->setInsurance(
            $vehicle_id, 
            $request['insurance_id']
        );

    }

    public function inspections($vehicle_id) {

        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Vehicle inspections',
            'vehicle' => $vehicle
        ];

        return $data;
    }

    public function storeInspection($vehicle_id, $request) {

        return $this->vehicleRepo->addInspection(
            $vehicle_id, 
            $request
        );

    }

    public function updateInspection($vehicle_id, $request) {

        return $this->vehicleRepo->updateInspection(
            $vehicle_id, 
            $request['inspection_id'],
            $request
        );

    }

    public function create()
    {
        $data = [
            'title' => 'Create vehicle',
            'references' => $this->vehicleRepo->getReferences()
        ];

        //dd($data);

        return $data;
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

        if( $file ) {
            $this->vehicleRepo->update(
                $vehicle_id, 
                ['profile_photo_id' => $file['file']['id']]
            );
        }

    }

}