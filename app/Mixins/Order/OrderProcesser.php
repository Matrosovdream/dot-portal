<?php
namespace App\Mixins\Order;

use App\Models\Order;

class OrderProcesser {

    public $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

}