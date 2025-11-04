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

    public function processPay( Request $request, $order_id )
    {
        $res = $this->actions->processPay( $order_id, $request );

        if( $res['success'] ) {
            return redirect()->route('dashboard.clearinghouse.index')
                            ->with('success', 'Payment processed successfully.');
        } else {
            return redirect()->back()
                            ->with('errors', [$res['message'] ?? 'Payment processing failed.']);
        }

    }

}
