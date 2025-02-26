<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Repositories\User\UserAddressRepo;
use App\Repositories\User\UserCompanyRepo;
use App\Models\User;

class UserRepo extends AbstractRepo
{

    private $userAdressRepo;
    private $userCompanyRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new User();

        $this->userAdressRepo = new UserAddressRepo();
        $this->userCompanyRepo = new UserCompanyRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'firstname' => $item->firstname,
            'lastname' => $item->lastname,
            'email' => $item->email,
            'phone' => $item->phone,
            'birthday' => $item->birthday,
            'is_active' => $item->is_active,
            'address' => $this->userAdressRepo->mapItem( $item->address ),
            'company' => $this->userCompanyRepo->mapItem( $item->company ),
            'Model' => $item
        ];

        return $res;
    }

}