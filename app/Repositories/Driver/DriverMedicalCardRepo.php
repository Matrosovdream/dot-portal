<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverMedicalCard;


class DriverMedicalCardRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new DriverMedicalCard();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'examiner_name' => $item->examiner_name,
            'national_registry' => $item->national_registry,
            'issue_date' => $item->issue_date,
            'expiration_date' => $item->expiration_date,
            'Model' => $item
        ];

        return $res;
    }

}