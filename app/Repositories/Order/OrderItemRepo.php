<?php
namespace App\Repositories\Order;

use App\Repositories\AbstractRepo;
use App\Models\OrderItem;

class OrderItemRepo extends AbstractRepo
{
    protected $model;

    protected $fields = [];
    protected $links = [];

    public function __construct()
    {
        $this->model = new OrderItem();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'order_id' => $item->order_id,
            'item_name' => $item->item_name,
            'item_description' => $item->item_description,
            'entity' => $item->entity,
            'entity_id' => $item->entity_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'total_price' => $item->total_price,
            //'Model' => $item
        ];

        return $res;

    }

}