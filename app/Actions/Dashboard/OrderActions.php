<?php
namespace App\Actions\Dashboard;

use App\Repositories\Order\OrderRepo;
use App\Repositories\User\UserRepo;


class OrderActions {

    public function __construct(
        private OrderRepo $orderRepo,
        private UserRepo $userRepo
    )
    {

    }

    public function showPay( $order_id, $request )
    {
//dd($this->orderRepo->getById( $order_id ));

        $user = $this->userRepo->getByID( auth()->user()->id );

        return [
            'title' => 'Pay for Order #' . $order_id,
            'order' => $this->orderRepo->getById( $order_id ),
            'paymentCards' => $user['paymentCards'],
        ];

    }


}