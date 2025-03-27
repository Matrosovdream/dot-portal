<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\Driver\DriverLicenseRepo;
use App\Repositories\Driver\DriverAddressRepo;
use App\Repositories\Driver\DriverMedicalCardRepo;
use App\Repositories\References\RefDriverTypeRepo;
use App\Repositories\References\RefCountryStateRepo;
use App\Repositories\References\RefDriverLicenseTypeRepo;
use App\Repositories\References\RefDriverLicenseEndrsRepo;
use App\Mixins\File\FileStorage;


class DriverUserActions {

    private $driverRepo;
    private $driverLicenseRepo;
    private $driverAddressRepo;
    private $driverMedicalCardRepo;
    private $refDriverTypeRepo;
    private $refStateRepo;
    private $refDriverLicenseTypeRepo;
    private $refDriverLicenseEndrsRepo;
    protected $fileStorage;

    public function __construct()
    {
        $this->driverRepo = new DriverRepo();
        $this->driverLicenseRepo = new DriverLicenseRepo();
        $this->driverAddressRepo = new DriverAddressRepo();
        $this->driverMedicalCardRepo = new DriverMedicalCardRepo();

        // References
        $this->refDriverTypeRepo = new RefDriverTypeRepo();
        $this->refStateRepo = new RefCountryStateRepo();
        $this->refDriverLicenseTypeRepo = new RefDriverLicenseTypeRepo();
        $this->refDriverLicenseEndrsRepo = new RefDriverLicenseEndrsRepo();

        $this->fileStorage = new FileStorage();

    }

    public function index()
    {

        // Get drivers by user
        $drivers = $this->driverRepo->getUserDrivers( 
            auth()->user()->id, 
            $paginate = 10 
        );

        $data = [
            'title' => 'My drivers',
            'drivers' => $drivers,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function show($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver details',
            'driver' => $driver,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function update($driver_id, $request)
    {
        $data = $this->driverRepo->update($driver_id, $request);

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create driver',
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        //dd($data);

        return $data;
    }

    public function store($request)
    {

        $data = $this->driverRepo->create($request);

        return $data;
    }

    public function destroy($driver_id)
    {
        $data = $this->driverRepo->delete($driver_id);

        return $data;
    }

    public function profile($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver profile',
            'driver' => $driver,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function updateProfile($driver_id, $request)
    {
        $data = $this->driverRepo->update($driver_id, $request);
        return $data;
    }

    public function license($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver license',
            'driver' => $driver,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function updateLicense($driver_id, $request)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        // Driver document from request
        $file = request()->file('license_file');

        $this->fileStorage->uploadFile(
            'license_file', 
            'drivers/' . $driver_id . '/license',
            'public',
        );


        dd($file);

        // If isset license
        if ( $driver['license'] ) {
            $data = $this->driverLicenseRepo->update($driver['license']['id'], $request);
        } else {
            $request['driver_id'] = $driver_id;
            $data = $this->driverLicenseRepo->create($request);
        }

        return $data;
    }

    public function address($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver address',
            'driver' => $driver,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function updateAddress($driver_id, $request)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        // If isset address
        if ( $driver['address'] ) {
            $data = $this->driverAddressRepo->update($driver['address']['id'], $request);
        } else {
            $request['item_id'] = $driver_id; dd($request);
            $data = $this->driverAddressRepo->create($request);
        }

        return $data;
    }

    public function medicalCard($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver medical card',
            'driver' => $driver,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function updateMedicalCard($driver_id, $request)
    {

        $driver = $this->driverRepo->getByID($driver_id);

        // If isset medical card
        if ( $driver['medicalCard'] ) {
            $data = $this->driverMedicalCardRepo->update($driver['medicalCard']['id'], $request);
        } else {
            $request['driver_id'] = $driver_id;
            $data = $this->driverMedicalCardRepo->create($request);
        }

        return $data;
    }

    public function logs($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver logs',
            'driver' => $driver,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function getReferences()
    {
        $references = [
            'driverType' => $this->refDriverTypeRepo->getAll([], $paginate=100),
            'state' => $this->refStateRepo->getAll([], $paginate=100),
            'licenseEndrs' => $this->refDriverLicenseEndrsRepo->getAll([], $paginate=100),
        ];

        return $references;
    }

}