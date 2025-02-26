<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserCompany;
use App\Repositories\References\RefCountryStateRepo;



class UserCompanyRepo extends AbstractRepo
{

    protected $userCompanyAddressRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserCompany();

        // References
        $this->userCompanyAddressRepo = new UserCompanyAddressRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }
        
        $addresses = [
            'business' => $this->userCompanyAddressRepo->mapItem( $item->businessAddress->first() ),
            'mailing' => $this->userCompanyAddressRepo->mapItem( $item->mailingAddress->first() ),
        ];

        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'phone' => $item->phone,
            'dot_number' => $item->dot_number,
            'mc_number' => $item->mc_number,
            'addresses' => $addresses,
            'Model' => $item
        ];

        return $res;
    }

}