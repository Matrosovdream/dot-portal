<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserPaymentCardMeta;



class UserPaymentCardMetaRepo extends AbstractRepo
{

    protected $countryStateRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new UserPaymentCardMeta();
    }

    public function mapItems($items)
    {
        if( empty($items) ) {
            return null;
        }

        $items = parent::mapItems($items);
        
        // Map so key => value
        foreach ($items['items'] as $key=>$item) {
            $items[ $item['key'] ] = $item['value'];
        }

        unset($items['items']);
        unset($items['Model']);

        return $items;

    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'subscription_id' => $item->user_id,
            'key' => $item->key,
            'value' => $item->value
        ];

        return $res;
    }

}