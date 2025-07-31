<?php
namespace App\Repositories\Subscription;

use App\Repositories\AbstractRepo;
use App\Models\PlanFeeModel;


class PlanFeeRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    protected $roleRepo;

    public function __construct()
    {
        $this->model = new PlanFeeModel();

        //$this->roleRepo = new UserRoleRepo();
    }

    public function getPrimary() {
        return $this->mapItem( $this->model->first() );
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'discount' => $item->discount,
            'short_description' => $item->short_description,
            'description' => $item->description,
            'user_role_id' => $item->user_role_id,
            'role' => $item->role ? $this->roleRepo->mapItem($item->role) : null,
            'Model' => $item
        ];

        return $res;
    }

}