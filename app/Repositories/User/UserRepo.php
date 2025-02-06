<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\User;



class UserRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new User();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'comment' => $item->comment,
            'user_id' => $item->user_id,
            'Model' => $item
        ];

        return $res;
    }

}