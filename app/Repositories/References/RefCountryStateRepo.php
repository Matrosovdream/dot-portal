<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefCountryStates;


class RefCountryStateRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new RefCountryStates();
    }

    public function mapItem($item)
    {
        if( empty($item) ) {
            return null;
        }
        
        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'code' => $item->code,
            'country_id' => $item->country_id,
            'Model' => $item
        ];
        return $res;
    }

}