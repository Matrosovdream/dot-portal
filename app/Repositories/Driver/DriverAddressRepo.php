<?php
namespace App\Repositories\Driver;

use App\Repositories\AbstractRepo;
use App\Models\DriverAddress;


class DriverAddressRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new DriverAddress();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'address1' => $item->address_1,
            'address2' => $item->address_2,
            'city' => $item->city,
            'state_id' => $item->state_id,
            'zip' => $item->zip,
            'Model' => $item
        ];

        return $res;
    }

}