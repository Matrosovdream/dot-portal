<?php
namespace App\Actions\Dashboard;

use App\Repositories\Driver\DriverRepo;
use App\Repositories\Driver\DriverLicenseRepo;
use App\Repositories\Driver\DriverAddressRepo;
use App\Repositories\Driver\DriverMedicalCardRepo;
use App\Repositories\References\RefDriverTypeRepo;
use App\Repositories\References\RefCountryStateRepo;
use App\Repositories\References\RefDriverLicenseTypeRepo;
use App\Repositories\References\RefDriverLicenseEndrsRepo;
use App\Mixins\File\FileStorage;
use App\Repositories\User\UserRepo;


class DriverUserActions {

    private $driverRepo;
    private $driverLicenseRepo;
    private $driverAddressRepo;
    private $driverMedicalCardRepo;
    private $refDriverTypeRepo;
    private $refStateRepo;
    private $refDriverLicenseTypeRepo;
    private $refDriverLicenseEndrsRepo;
    private $userRepo;
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
        $this->userRepo = new UserRepo();

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
            'drivers' => $drivers
        ];

        return $data;
    }

    public function show($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver details',
            'driver' => $driver,
            'references' => $this->getReferences()
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
            'references' => $this->getReferences()
        ];

        //dd($data);

        return $data;
    }

    public function store($request)
    {

        $data = $this->driverRepo->create($request);

        if( !isset($data['error']) ) {
            // Save profile photo from request
            $this->saveProfilePhoto($driver_id = $data['id']);
        }

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
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function updateProfile($driver_id, $request)
    {

        $data = [];
        $driver = $this->driverRepo->getByID($driver_id);

        // Update general data
        if( $request['action'] == 'update_general' ) {
            $data = $this->driverRepo->update($driver_id, $request);

            // Save profile photo from request
            $this->saveProfilePhoto($driver_id);
        }

        // Update user
        if( $request['action'] == 'update_user' ) {
            $user = $this->userRepo->update($driver['user']['id'], $request);
        }

        // Update password
        if( $request['action'] == 'update_password' ) {

            if( !empty($request['new_password']) ) {
                $dataSet = ['password' => bcrypt($request['new_password'])];   
                $user = $this->userRepo->update($driver['user']['id'], $dataSet);
            }
            
        }

        return $data;
    }

    public function license($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver license',
            'driver' => $driver,
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function updateLicense($driver_id, $request)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        // Driver document from request
        $tags = ['driver license'];

        $file = $this->fileStorage->uploadFile(
            'license_file', 
            'drivers/' . $driver_id . '/license',
            'local',
            ['tags' => $tags]
        );

        if( isset($file['file']['id']) ) {

            // Add document, in our case license            
            $this->driverRepo->addDocument(
                $driver_id, 
                $file['file'], 
                'license',
                $removeOld = true
            );

        }

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
            'references' => $this->getReferences()
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
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function updateMedicalCard($driver_id, $request)
    {

        $driver = $this->driverRepo->getByID($driver_id);

        // Driver document from request
        $tags = ['medical card'];

        $file = $this->fileStorage->uploadFile(
            'medical_card', 
            'drivers/' . $driver_id . '/medical_card',
            'local',
            ['tags' => $tags]
        );
        if( $file ) {

            // Add document, in our case license
            $this->driverRepo->addDocument(
                $driver_id, 
                $file['file'], 
                'medical_card',
                $removeOld = true
            );

        }

        // If isset medical card
        if ( $driver['medicalCard'] ) {
            $data = $this->driverMedicalCardRepo->update($driver['medicalCard']['id'], $request);
        } else {
            $request['driver_id'] = $driver_id;
            $data = $this->driverMedicalCardRepo->create($request);
        }

        return $data;
    }

    public function drugtest($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver drug test',
            'driver' => $driver,
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function updateDrugtest($driver_id, $request)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        return $this->driverRepo->updateDrugtest(
            $driver_id, 
            $request,
            $files = [
                'drugtest_file' => 'drugtest_file'
            ]
        );
        
    }

    public function logs($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver logs',
            'driver' => $driver,
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function saveProfilePhoto($driver_id)
    {
        // Driver document from request
        $file = $this->fileStorage->uploadFile(
            'profile_photo', 
            'drivers/' . $driver_id . '/profile',
            'local',
            ['tags' => ['profile photo']]
        );

        if( $file['file'] ) {

            $this->driverRepo->update(
                $driver_id, 
                ['profile_photo_id' => $file['file']['id']]
            );
        }

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