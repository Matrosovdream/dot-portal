<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\Driver;
use App\Repositories\Driver\DriverHistoryRepo;
use App\Repositories\Driver\DriverDocumentRepo;
use App\Repositories\Driver\DriverAddressRepo;
use App\Repositories\Driver\DriverLicenseRepo;
use App\Repositories\Driver\DriverMedicalCardRepo;
use App\Repositories\Driver\DriverDrugTestRepo;
use App\Repositories\Driver\DriverMvrRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\File\FileRepo;
use App\Mixins\File\FileStorage;


class DriverRepo extends AbstractRepo
{

    protected $historyRepo;
    protected $userRepo;
    protected $documentRepo;
    protected $addressRepo;
    protected $licenseRepo;
    protected $cdlLicenseRepo;
    protected $medicalCardRepo;
    protected $fileRepo;
    protected $drugTestRepo;
    protected $mvrRepo;
    protected $fileStorage;

    protected $fields = [];

    protected $withRelations = [
        'user',
        'history',
        'documents',
        'address',
        'license',
        'cdlLicense',
        'medicalCard',
        'profilePhoto',
        'drugTest',
    ];

    public function __construct()
    {
        // Model
        $this->model = new Driver();

        $this->fileRepo = new FileRepo();
        $this->fileStorage = new FileStorage;

        // Relations repositories
        $this->historyRepo = new DriverHistoryRepo();
        $this->userRepo = new UserRepo();
        $this->documentRepo = new DriverDocumentRepo();
        $this->addressRepo = new DriverAddressRepo();
        $this->licenseRepo = new DriverLicenseRepo();
        $this->cdlLicenseRepo = new DriverCdlLicenseRepo();
        $this->medicalCardRepo = new DriverMedicalCardRepo();
        $this->drugTestRepo = new DriverDrugTestRepo();
        $this->mvrRepo = new DriverMvrRepo();
        
    }

    public function beforeCreate($data)
    {
        if( empty($data['company_id']) ) {
            $data['company_id'] = auth()->user()->id;
        }

        return $data;
    }

    public function create($data)
    {

        // If user exists by email
        $user = $this->userRepo->getByEmail($data['email']);

        if( $user ) {
            return [
                'error' => true,
                'message' => 'User with this email already exists'
            ];
        } else {

            // Create user
            $user = $this->userRepo->create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'] ?? '',
                'is_active' => 1
            ]);
            $data['user_id'] = $user['id'];

            // Set user role
            $user['Model']->setRole('driver');

            unset($data['firstname'], $data['lastname'], $data['middlename'], $data['email'], $data['phone']);

        }

        // Call parent method create()
        return parent::create($data);

    }

    public function getByUserID($user_id)
    {
        $item = $this->model
            ->with($this->withRelations)
            ->where('user_id', $user_id)
            ->first();

        return $this->mapItem($item);
    }

    public function getUserDrivers($user_id, $paginate = 10)
    {
        $items = $this->model
            ->with($this->withRelations)
            ->where(function ($query) use ($user_id) {
                $query->where('company_id', $user_id)
                    ->orWhereNull('company_id');
            })
            ->paginate($paginate);

        return $this->mapItems($items);
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


    public function addDocument($driver_id, $file, $type, $removeOld = false)
    {

        if( !$file ) { return null; }

        $driver = $this->getByID($driver_id);

        $data = [
            'type' => $type,
            'title' => $file['title'],
            'file_id' => $file['id'],
            'extension' => $file['extension'],
        ];

        // Remove old document
        if( $removeOld ) {
            $this->removeDocument($driver_id, $type);
        }

        return $driver['Model']->documents()->create( $data );
    }

    public function removeDocument($driver_id, $type)
    {

        $driver = $this->getByID($driver_id);

        $issetDocuments = $driver['documents']['groupType'][ $type ] ?? null;
        if( !$issetDocuments ) { return null; }

        foreach ($issetDocuments as $document) {

            // Remove from document repo
            $this->documentRepo->delete($document['id']);

            // Remove from file repo
            $this->fileRepo->delete($document['file']['id']);
        }

    }

    public function updateDrugtest($driver_id, $request, $files=[])
    {

        $driver = $this->getByID($driver_id);

        if( isset($driver['drugTest']) ) {
            $this->drugTestRepo->update( $driver['drugTest']['id'], $request );
        } else {
            $request['driver_id'] = $driver_id;
            $this->drugTestRepo->create( $request );
        }

        $vehicle = $this->getByID($driver_id);

        // Upload files
        if( isset($files['drugtest_file']) ) {
            
            $tags = ['Driver drug test', 'Driver #' . $driver_id];

            $file = $this->fileStorage->uploadFile(
                $files['drugtest_file'], 
                'drivers/' . $driver_id . '/drugtest',
                'local',
                ['tags' => $tags]
            );

            if( isset($file['file']['id']) ) {
                $this->drugTestRepo->update( $vehicle['drugTest']['id'], ['file_id' => $file['file']['id']]);
            }

        }

        return $vehicle['drugTest'];

    }

    public function updateCdlLicense($driver_id, $request, $files=[])
    {

        $driver = $this->getByID($driver_id);

        if( isset($driver['cdlLicense']) ) {
            $this->cdlLicenseRepo->update( $driver['cdlLicense']['id'], $request );
        } else {
            $request['driver_id'] = $driver_id;
            $this->cdlLicenseRepo->create( $request );
        }

        $vehicle = $this->getByID($driver_id);

        // Upload files
        if( isset($files['license_file']) ) {
            
            $tags = ['Driver CDL License', 'Driver CDL License #' . $driver_id];

            $file = $this->fileStorage->uploadFile(
                $files['license_file'], 
                'drivers/' . $driver_id . '/cdl_license',
                'local',
                ['tags' => $tags]
            );

            if( isset($file['file']['id']) ) {
                $this->cdlLicenseRepo->update( $vehicle['cdlLicense']['id'], ['file_id' => $file['file']['id']]);
            }

        }

        return $vehicle['cdlLicense'];

    }

    public function countDriversByCompany($company_id)
    {
        return $this->model
            ->where('company_id', $company_id)
            ->count();
    }

    public function updateStatus($driver_id, $status_id)
    {
        $driver = $this->getByID($driver_id);

        if( !$driver ) { return null;}

        $driver['Model']->status_id = $status_id;
        $driver['Model']->save();

        return $this->mapItem($driver['Model']->fresh());
    }

    public function updateMvr($driver_id, $request, $files=[])
    {

        $driver = $this->getByID($driver_id);

        if( isset($driver['mvr']) ) {
            $this->mvrRepo->update( $driver['mvr']['id'], $request );
        } else {
            $request['driver_id'] = $driver_id;
            $this->mvrRepo->create( $request );
        }

        $driver = $this->getByID($driver_id);

        // Upload files
        if( isset($files['mvr']) ) {
            
            $tags = ['Drive MVR', 'Driver MVR #' . $driver_id];

            $file = $this->fileStorage->uploadFile(
                $files['mvr'], 
                'drivers/' . $driver_id . '/mvr',
                'local',
                ['tags' => $tags]
            );

            if( isset($file['file']['id']) ) {
                $this->mvrRepo->update( $driver['mvr']['id'], ['file_id' => $file['file']['id']]);
            }

        }

        return $driver['mvr'];

    }

    public function removeMvrDocument( $drive_id ) {

        $driver = $this->getByID($drive_id);

        if( isset($driver['mvr']) && isset($driver['mvr']['file_id']) ) {
            $this->fileRepo->delete( $driver['mvr']['file_id'] );
            $this->mvrRepo->update( $driver['mvr']['id'], ['file_id' => null] );
        }

        return true;

    }

    public function getCdlLicense($driver_id)
    {
        return $this->cdlLicenseRepo->getByDriverID($driver_id);
    }


    public function mapItem($item)
    {

        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'dob' => $item->dob,
            'ssn' => $item->ssn,
            'hire_date' => $item->hire_date,
            'driver_type_id' => $item->driver_type_id,
            'user_id' => $item->user_id,
            'status_id' => $item->status_id,
            'isTerminated' => $item->status_id === 3,
            'Model' => $item
        ];

        $res = array_merge($res, $this->mapRelations($item));

        $res['firstname'] = $res['user']['firstname'];
        $res['lastname'] = $res['user']['lastname'];
        $res['email'] = $res['user']['email'];

        return $res;
    }

    public function mapRelations($item)
    {

        $res = [
            'user' => $this->userRepo->mapItem( $item['user'] ),
            'userCompany' => $this->userRepo->mapItem( $item['userCompany'] ),
            'history' => $this->historyRepo->mapItem( $item['history']->first() ),
            'documents' => $this->documentRepo->mapItems( $item['documents'] ),
            'address' => $this->addressRepo->mapItem( $item['address'] ),
            'license' => $this->licenseRepo->mapItem( $item['license'] ),
            'cdlLicense' => $this->cdlLicenseRepo->mapItem( $item['cdlLicense'] ),
            'medicalCard' => $this->medicalCardRepo->mapItem( $item['medicalCard'] ),
            'drugTest' => $this->drugTestRepo->mapItem( $item['drugTest'] ),
            'mvr' => $this->mvrRepo->mapItem( $item->mvr ),
            'profilePhoto' => $this->fileRepo->mapItem( $item['profilePhoto'] )
        ];

        return $res;
    }

}