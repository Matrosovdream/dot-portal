<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverLicense;


class DriverLicenseRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new DriverLicense();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'type_id' => $item->type_id,
            'endorsement_id' => $item->endorsement_id,
            'license_number' => $item->license_number,
            'expiration_date' => $item->expiration_date,
            'state_id' => $item->state_id,
            'Model' => $item
        ];

        return $res;
    }

}