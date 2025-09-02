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

        return view(
            'dashboard.clearinghouse.index', 
            $this->actions->index( $request )
        );

    }

}
