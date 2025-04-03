<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\Driver;
use App\Repositories\Driver\DriverHistoryRepo;
use App\Repositories\Driver\DriverDocumentRepo;
use App\Repositories\Driver\DriverAddressRepo;
use App\Repositories\Driver\DriverLicenseRepo;
use App\Repositories\Driver\DriverMedicalCardRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\File\FileRepo;

class DriverRepo extends AbstractRepo
{

    protected $historyRepo;
    protected $userRepo;
    protected $documentRepo;
    protected $addressRepo;
    protected $licenseRepo;
    protected $medicalCardRepo;
    protected $fileRepo;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = [
        'user',
        'history',
        'documents',
        'address',
        'license',
        'medicalCard',
        'profilePhoto'
    ];

    public function __construct()
    {
        // Model
        $this->model = new Driver();

        $this->fileRepo = new FileRepo();

        // Relations repositories
        $this->historyRepo = new DriverHistoryRepo();
        $this->userRepo = new UserRepo();
        $this->documentRepo = new DriverDocumentRepo();
        $this->addressRepo = new DriverAddressRepo();
        $this->licenseRepo = new DriverLicenseRepo();
        $this->medicalCardRepo = new DriverMedicalCardRepo();
        
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
                'password' => $data['password'],
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

    public function addDocument($driver_id, $file, $type)
    {

        if( !$file ) { return null; }

        $driver = $this->getByID($driver_id);

        $data = [
            'type' => $type,
            'title' => $file['title'],
            'file_id' => $file['id'],
            'extension' => $file['extension'],
        ];

        return $driver['Model']->documents()->create( $data );
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
            'medicalCard' => $this->medicalCardRepo->mapItem( $item['medicalCard'] ),
            'profilePhoto' => $this->fileRepo->mapItem( $item['profilePhoto'] )
        ];

        return $res;
    }

}