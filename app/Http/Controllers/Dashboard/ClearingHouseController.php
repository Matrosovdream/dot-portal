<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\ClearingHouseActions;


class ClearingHouseController extends Controller
{

    public function __construct(
        private ClearingHouseActions $actions
    )
    {}

    public function index( Request $request )
    {

        if( $request->has('proto') ) {
            $template = 'dashboard.clearinghouse.proto';
        } else {
            $template = 'dashboard.clearinghouse.index';
        }
        
        return view(
            $template,
            $this->actions->index( $request )
        );

    }

    public function buyQueriesIndex( Request $request )
    {
        return view(
            'dashboard.clearinghouse.buyqueries.index',
            $this->actions->buyQueriesForm( $request )
        );
    }

    public function buyQueriesProcess( Request $request )
    {

        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);
       
        $res = $this->actions->buyQueriesProcess( $validated );
        $order = $res['order'] ?? null;

        if( 
            $res['success'] && $order
            ) {
            return redirect()->route('dashboard.orders.show.pay', ['order_id' => $order['id'] ]);
        } else {
            return redirect()
                ->back()
                ->withErrors( [ 'general' => $res['message'] ] );
        }

    }

}
