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

class DriverRepo extends AbstractRepo
{

    protected $historyRepo;
    protected $userRepo;
    protected $documentRepo;
    protected $addressRepo;
    protected $licenseRepo;
    protected $medicalCardRepo;

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
    ];

    public function __construct()
    {
        // Model
        $this->model = new Driver();

        // Relations repositories
        $this->historyRepo = new DriverHistoryRepo();
        $this->userRepo = new UserRepo();
        $this->documentRepo = new DriverDocumentRepo();
        $this->addressRepo = new DriverAddressRepo();
        $this->licenseRepo = new DriverLicenseRepo();
        $this->medicalCardRepo = new DriverMedicalCardRepo();
    }

    public function getUserDrivers($user_id, $paginate = 10)
    {
        $items = $this->model
            ->with($this->withRelations)
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                    ->orWhereNull('user_id');
            })
            ->paginate($paginate);

        return $this->mapItems($items);
    }

    public function addDocument($driver_id, $file, $type)
    {
        $driver = $this->getByID($driver_id);

        $data = [
            'type' => $type,
            'title' => $file['title'],
            'file_id' => $file['id'],
            'extension' => $file['extension'],
        ];

        return $driver['Model']->documents()->create( $data );
    }


    public function beforeCreate($data)
    {
        // Add current user ID
        $data['user_id'] = auth()->user()->id;
        return $data;
    }

    public function mapItem($item)
    {
        $res = [
            'id' => $item->id,
            'firstname' => $item->firstname,
            'middlename' => $item->middlename,
            'lastname' => $item->lastname,
            'phone' => $item->phone,
            'email' => $item->email,
            'dob' => $item->dob,
            'ssn' => $item->ssn,
            'hire_date' => $item->hire_date,
            'driver_type_id' => $item->driver_type_id,
            'user_id' => $item->user_id,
            'Model' => $item
        ];

        $res = array_merge($res, $this->mapRelations($item));

        return $res;
    }

    public function mapRelations($item)
    {

        $res = [
            'user' => $this->userRepo->mapItem( $item['user']->first() ),
            'history' => $this->historyRepo->mapItem( $item['history']->first() ),
            'documents' => $this->documentRepo->mapItems( $item['documents'] ),
            'address' => $this->addressRepo->mapItem( $item['address'] ),
            'license' => $this->licenseRepo->mapItem( $item['license'] ),
            'medicalCard' => $this->medicalCardRepo->mapItem( $item['medicalCard'] ),
        ];

        return $res;
    }

}