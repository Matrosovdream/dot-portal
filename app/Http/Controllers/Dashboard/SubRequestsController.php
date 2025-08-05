<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\SubRequestsActions;

class SubRequestsController extends Controller
{

    public function __construct(
        private SubRequestsActions $actions)
    {

    }

    public function index()
    {
        return view('dashboard.subrequests.index', $this->actions->index());
    }

    public function create()
    {
        return view('dashboard.subrequests.create', $this->actions->create());
    }

    public function show($req_id)
    {
        return view('dashboard.subrequests.show', $this->actions->show($req_id));
    }

    public function update($req_id, Request $request)
    {
        $res = $this->actions->update($req_id, $request);
        if( $res ) {
            return redirect()->route('dashboard.subrequests.show', $req_id);
        }
    }

}
