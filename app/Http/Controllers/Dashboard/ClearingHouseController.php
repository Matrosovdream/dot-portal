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

}
