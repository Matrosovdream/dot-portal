<?php
namespace App\Actions\Dashboard;

use App\Repositories\Driver\DriverRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\Order\OrderRepo;
use App\Repositories\User\UserQueryBalanceRepo;
use App\Models\Order;
use App\Services\References\RefQueryPriceService;


class ClearingHouseActions {

    public function __construct(
        private DriverRepo $driverRepo,
        private UserRepo $userRepo,
        private OrderRepo $orderRepo,
        private UserQueryBalanceRepo $userQueryBalanceRepo,
        private RefQueryPriceService $refQueryPriceService
    )
    {

    }

    public function index( $request )
    {

        // Test code for observer on order status change
        if( $request->has('test') ) {
            
            $order_id = 1;
            $order = Order::find( $order_id );

            $order->status_id = 2; // Completed
            $order->save();

            // Imitate status change
            $order->status_id = 3; // Completed
            $order->save();

        }

        $user_id = auth()->user()->id;

        $user = $this->userRepo->getById( $user_id );
        $queryBalance = $this->userQueryBalanceRepo->getByTypeUser( 'chm', $user_id );
        $queryBalanceHistory = $this->userQueryBalanceRepo->getHistoryById( $queryBalance['id'] ?? 0 );

        $data = [
            'title' => 'Clearing House Management',
            'user' => $user,
            'company' => $user['company'] ?? null,
            'queryBalance' => $queryBalance,
            'queryBalanceHistory' => $queryBalanceHistory,
            'drivers' => $this->driverRepo->getUserDrivers( auth()->user()->id ),
        ];

        return $data;
    }

    public function buyQueriesForm( $request )
    {

        $data = [
            'title' => 'Buy Queries',
            'price_per_query' => 15.00,
        ];

        return $data;

    }

    public function buyQueriesProcess( $request )
    {

        $priceItem = $this->refQueryPriceService->getByUserId( 
            auth()->user()->id, 
            'chm' 
        );

        $query_amount = $request['amount'];
        $price_per_query = $priceItem['price_per_query'];
        $total_price = $query_amount * $price_per_query;

        // Add order with queries
        $orderLoad = [
            'user_id' => auth()->user()->id,
            'amount' => $total_price,
            'discount_amount' => 0.00,
            'status_id' => 1,
            'payment_method_id' => 1,
            'notes' => 'This is a test order for buy queries of ' . $query_amount . ' queries.',
            'items' => [
                [
                    'item_name' => $query_amount . ' Queries',
                    'entity' => 'chm_query',
                    'entity_id' => 0,
                    'quantity' => $query_amount,
                    'unit_price' => $price_per_query,
                    'price' => $total_price
                ],
            ],
        ];
        $order = $this->orderRepo->createWithPayload( $orderLoad );

        if( $order ) {
            return [
                'success' => true,
                'message' => 'Order created successfully. Order ID: ' . $order['id'],
                'order' => $order,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to create order.',
            ];
        }

    }

}