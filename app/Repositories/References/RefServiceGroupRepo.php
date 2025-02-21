<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefServiceGroup;


class RefServiceGroupRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new RefServiceGroup();
    }

    public function mapItem($item)
    {

        if( ! $item ) return null;
        
        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'description' => $item->description,
            'is_active' => $item->is_active,
            'Model' => $item
        ];
        return $res;
    }

}