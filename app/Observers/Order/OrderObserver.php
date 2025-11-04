<?php

namespace App\Observers\Order;

use App\Models\Order;
use App\Services\Order\OrderService;


class OrderObserver
{

    public function __construct(
        protected OrderService $orderService


    )
    { }

    public function created(Order $order): void
    {
        
    }

    public function updated(Order $order): void
    {
        // Handle after change status actions
        $this->orderService->afterChangeStatus( $order );
        

    }

    public function deleted(Order $order): void
    {
       
    }

    public function restored(Order $order): void
    {


    }

    public function forceDeleted(Order $order): void
    {
        //
    }

}
