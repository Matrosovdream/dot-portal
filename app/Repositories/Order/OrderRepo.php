<?php
namespace App\Repositories\Order;

use App\Repositories\AbstractRepo;
use App\Models\Order;
use App\Repositories\Order\OrderItemRepo;
use App\Repositories\Order\OrderPaymentRepo;
use App\Repositories\References\RefOrderStatusRepo;
use App\Repositories\References\RefPaymentMethodRepo;
use App\Repositories\User\UserRepo;


class OrderRepo extends AbstractRepo
{
    protected $model;

    protected $orderItemRepo;
    protected $orderPaymentRepo;
    protected $userRepo;
    protected $refOrderStatusRepo;
    protected $refPaymentMethodRepo;

    protected $fields = ['items', 'payments', 'status', 'paymentMethod', 'user'];
    protected $links = [];

    public function __construct()
    {
        $this->model = new Order();

        // Extras
        $this->orderItemRepo = new OrderItemRepo();
        $this->orderPaymentRepo = new OrderPaymentRepo();
        $this->userRepo = new UserRepo();

        // References
        $this->refOrderStatusRepo = new RefOrderStatusRepo();
        $this->refPaymentMethodRepo = new RefPaymentMethodRepo();

    }

    public function createWithPayload( $payload )
    {
        // Create order
        $order = parent::create( [
            'user_id' => $payload['user_id'],
            'amount' => $payload['amount'],
            'discount_amount' => $payload['discount_amount'],
            'status_id' => $payload['status_id'],
            'payment_method_id' => $payload['payment_method_id'],
            'notes' => $payload['notes'],
        ] );

        // Create order items
        if( isset( $payload['items'] ) && is_array( $payload['items'] ) ) {

            foreach( $payload['items'] as $item ) {

                $this->orderItemRepo->create( [
                    'order_id' => $order['id'],
                    'item_name' => $item['item_name'],
                    'item_description' => $item['item_description'] ?? null,
                    'entity' => $item['entity'],
                    'entity_id' => $item['entity_id'],
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $item['price'],
                    'total_price' => ( $item['quantity'] ?? 1 ) * $item['price'],
                ] );

            }

        }

        if( isset( $order['id'] ) ) {
            return $this->getById( $order['id'] );
        } else {
            return null;
        }

    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        $orderItems = $this->orderItemRepo->mapItems( $item->items );

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'user' => $this->userRepo->mapItem( $item->user ),
            'order_number' => $item->order_number,
            'amount' => $item->amount,
            'discount_amount' => $item->discount_amount,
            'status_id' => $item->status_id,
            'status' => $this->refOrderStatusRepo->mapItem( $item->status ),
            'payment_method_id' => $item->payment_method_id,
            'payment_method' => $this->refPaymentMethodRepo->mapItem( $item->paymentMethod ),
            'notes' => $item->notes,
            //'items' => $orderItems,
            'item' => $orderItems['items'][0] ?? null,
            'payments' => $this->orderPaymentRepo->mapItems( $item->payments ),
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'Model' => $item
        ];

        return $res;

    }

}