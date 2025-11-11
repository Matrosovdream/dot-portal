<?php

namespace App\Repositories\References;

use App\Models\RefQueryPrice;
use App\Repositories\AbstractRepo;


class RefQueryPriceRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new RefQueryPrice();
    }

    public function getByType( $type )
    {
        $item = $this->model->where( 'type', $type )->first();
        if( $item ) {
            return $this->mapItem( $item );
        }
        return null;
    }

    public function mapItem($item)
    {
        $res = [
            'id' => $item->id,
            'type' => $item->type,
            'price_per_query' => $item->price_per_query,
            'rules' => $item->rules,
            'Model' => $item
        ];
        return $res;
    }

}