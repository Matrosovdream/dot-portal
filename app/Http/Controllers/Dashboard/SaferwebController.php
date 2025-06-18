<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\SaferwebActions;

class SaferwebController extends Controller
{

    public function __construct(
        private SaferwebActions $actions
    )
    {}

    public function inspections( Request $request )
    {

        return view(
            'dashboard.saferweb.inspections.index', 
            $this->actions->inspections()
        );

    }

    public function crashes( Request $request )
    {
        return view(
            'dashboard.saferweb.crashes.index', 
            $this->actions->crashes()
        );
    }

}
