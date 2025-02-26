<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserCompanyAddress;
use App\Repositories\References\RefCountryStateRepo;



class UserCompanyAddressRepo extends AbstractRepo
{

    protected $countryStateRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserCompanyAddress();

        // References
        $this->countryStateRepo = new RefCountryStateRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'address1' => $item->address1,
            'address2' => $item->address2,
            'city' => $item->city,
            'state' => $this->countryStateRepo->mapItem( $item->state ),
            'zip' => $item->zip,
            'full_address' => $this->mapFullAddress( $item ),
            'Model' => $item
        ];

        return $res;
    }

    public function mapFullAddress( $item ) {

        if( empty($item) ) {
            return null;
        }

        $parts = [
            $item->address1,
            $item->address2,
            $item->city,
            $item->state->name,
            $item->zip,
        ];

        return implode(', ', array_filter($parts));
    }

}