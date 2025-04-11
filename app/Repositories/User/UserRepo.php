<?php
namespace App\Repositories\User;

use Illuminate\Support\Str;
use App\Repositories\AbstractRepo;
use App\Repositories\User\UserAddressRepo;
use App\Repositories\User\UserCompanyRepo;
use App\Repositories\User\UserPaymentCardRepo;
use App\Repositories\User\UserMetaRepo;
use App\Models\User;



class UserRepo extends AbstractRepo
{

    private $userAdressRepo;
    private $userCompanyRepo;
    private $userPaymentCardRepo;
    private $metaRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = ['address', 'company', 'paymentCards'];

    public function __construct()
    {
        $this->model = new User();

        $this->userAdressRepo = new UserAddressRepo();
        $this->userCompanyRepo = new UserCompanyRepo();
        $this->userPaymentCardRepo = new UserPaymentCardRepo();
        $this->metaRepo = new UserMetaRepo();
    }

    // Set password random beforeCreate
    public function beforeCreate($data)
    {

        if( empty($data['password']) ) {
            $data['password'] = $this->generatePassword();
        }
        return $data;
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
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
            'paymentCards' => $this->userPaymentCardRepo->mapItems( $item->paymentCards ),
            'meta' => $this->metaRepo->mapItems( $item->meta ),
            'Model' => $item
        ];

        return $res;
    }

    protected function generatePassword()
    {
        $password = Str::random(8) . rand(1000, 9999) . '!@#';
        return bcrypt($password);
    }

}