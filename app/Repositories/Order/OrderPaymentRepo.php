<?php
namespace App\Repositories\Order;

use App\Repositories\AbstractRepo;
use App\Models\OrderPayment;

class OrderPaymentRepo extends AbstractRepo
{
    protected $model;

    protected $fields = [];
    protected $links = [];

    public function __construct()
    {
        $this->model = new OrderPayment();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'order_id' => $item->order_id,
            'payment_method_id' => $item->payment_method_id,
            'amount' => $item->amount,
            'status' => $item->status,
            'transaction_id' => $item->transaction_id,
            'transaction_details' => $item->transaction_details,
            'payment_date' => $item->payment_date,
            'Model' => $item
        ];

        return $res;

    }

}