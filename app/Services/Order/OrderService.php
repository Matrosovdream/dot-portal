<?php

namespace App\Services\Order;

use App\Repositories\Order\OrderRepo;
use App\Services\User\UserQueryBalanceService;

class OrderService {

    protected $orderRepo;
    protected $queryBalanceService;

    public function __construct(
    )
    { 
        $this->orderRepo = new OrderRepo();
        $this->queryBalanceService = new UserQueryBalanceService();
    }

    public function afterChangeStatus( $order )
    {

        switch( $order->status_id ) {
            case 3: // Completed
                $this->afterCompletedStatus( $order );
                break;
        }

        

    }

    public function afterCompletedStatus( $order )
    {
        
        $order = $this->orderRepo->getById( $order->id );
        $items = $order['items'];

        // Go through each item and perform necessary actions
        foreach(  $items as $item ) {
            
            if( $item['entity'] == 'chm_query' ) {

                $queryType = 'chm';

                $this->queryBalanceService->addBalanceUser( 
                    $order['user_id'], 
                    $queryType,
                    $item['quantity'],
                    [ 'initiator' => 'order', 'initiator_id' => $order['id'] ]
                );
            }

        }

    }

}