<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserQueryBalance;


class UserQueryBalanceRepo extends AbstractRepo
{

    public $model;

    protected $fields = [];

    protected $withRelations = [];

    protected $userRepo;
    protected $metaRepo;

    public function __construct()
    {
        $this->model = new UserQueryBalance();
    }

    public function getByTypeUser( $type, $user_id )
    {
        $query = $this->model->newQuery();

        $query->where('type', $type );
        $query->where('user_id', $user_id );

        $item = $query->first();

        return $this->mapItem( $item );
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'user_company_id' => $item->user_company_id,
            'type' => $item->type,
            'amount' => $item->amount,
            'Model' => $item
        ];

        return $res;
    }

}