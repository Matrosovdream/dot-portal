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
            $this->actions->inspections( $request )
        );

    }

    public function inspectionsShow( $inspection_id, Request $request )
    { 
        return view(
            'dashboard.saferweb.inspections.show', 
            $this->actions->inspectionsShow( $inspection_id, $request )
        );
    }

    public function crashes( Request $request )
    {
        return view(
            'dashboard.saferweb.crashes.index', 
            $this->actions->crashes( $request )
        );
    }

    public function crashesShow( $crash_id, Request $request )
    {
        return view(
            'dashboard.saferweb.crashes.show', 
            $this->actions->crashesShow( $crash_id, $request )
        );
    }

}
