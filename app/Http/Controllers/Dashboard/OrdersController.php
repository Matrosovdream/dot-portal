<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\OrderActions;


class OrdersController extends Controller
{

    public function __construct(
        private OrderActions $actions
    )
    {}

    public function showPay( Request $request, $order_id )
    {
        return view(
            'dashboard.orders.show.pay',
            $this->actions->showPay( $order_id, $request )
        );
    }

}
