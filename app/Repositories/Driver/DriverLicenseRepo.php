<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverLicense;
use App\Repositories\References\RefDriverTypeRepo;
use App\Repositories\References\RefCountryStateRepo;    
use App\Repositories\References\RefDriverLicenseEndrsRepo;


class DriverLicenseRepo extends AbstractRepo
{

    protected $refDriverTypeRepo;
    protected $refStateRepo;
    protected $refDriverLicenseEndrsRepo;

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new DriverLicense();

        // References
        $this->refDriverTypeRepo = new RefDriverTypeRepo();
        $this->refStateRepo = new RefCountryStateRepo();
        $this->refDriverLicenseEndrsRepo = new RefDriverLicenseEndrsRepo();
    
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }
        
        $res = [
            'id' => $item->id,
            'type_id' => $item->type_id,
            'driverType' => $this->refDriverTypeRepo->mapItem( $item->type->first() ),
            'endorsement_id' => $item->endorsement_id,
            'endorsement' => $this->refDriverLicenseEndrsRepo->mapItem($item->endorsement->first()),
            'license_number' => $item->license_number,
            'expiration_date' => $item->expiration_date,
            'state_id' => $item->state_id,
            'state' => $this->refStateRepo->mapItem($item->countryState->first()),
            'Model' => $item
        ];

        return $res;
    }

}