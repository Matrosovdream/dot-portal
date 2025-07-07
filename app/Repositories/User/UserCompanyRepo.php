<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserCompany;
use App\Repositories\User\UserCompanyAddressRepo;
use App\Repositories\User\CompanySaferwebRepo;



class UserCompanyRepo extends AbstractRepo
{

    protected $userCompanyAddressRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    protected $saferwebRepo;

    public function __construct()
    {
        $this->model = new UserCompany();

        $this->saferwebRepo = new CompanySaferwebRepo();

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
            'user_id' => $item->user_id,
            'name' => $item->name,
            'phone' => $item->phone,
            'dot_number' => $item->dot_number,
            'mc_number' => $item->mc_number,
            'addresses' => $addresses,
            'saferweb' => $this->saferwebRepo->mapItem( $item->saferweb ),
            'Model' => $item
        ];

        return $res;
    }

}