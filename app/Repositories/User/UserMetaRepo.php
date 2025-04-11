<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserMeta;



class UserMetaRepo extends AbstractRepo
{

    protected $countryStateRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserMeta();
    }

    public function mapItems($items)
    {
        if( empty($items) ) {
            return null;
        }

        $items = parent::mapItems($items);
        
        // Map so key => value
        foreach ($items['items'] as $key=>$item) {
            $items['items'][ $item['key'] ] = $item['value'];
            unset($items['items'][ $key ]);
        }

        return $items;

    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'key' => $item->key,
            'value' => $item->value,
            'Model' => $item
        ];

        return $res;
    }

}