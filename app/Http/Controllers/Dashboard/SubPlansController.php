<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\SubPlansActions;

class SubPlansController extends Controller
{

    public function __construct(
        private SubPlansActions $actions)
    {

    }

    public function index()
    {
        return view('dashboard.subplans.index', $this->actions->index());
    }

    public function create()
    {
        return view('dashboard.subplans.create', $this->actions->create());
    }

    public function store(Request $request)
    {
        $res = $this->actions->store($request);
        if( $res ) {
            return redirect()->route('dashboard.subplans.index');
        }
    }

    public function show($plan_id)
    {
        return view('dashboard.subplans.show', $this->actions->show($plan_id));
    }

    public function update($plan_id, Request $request)
    {
        $res = $this->actions->update($plan_id, $request);
        if( $res ) {
            return redirect()->route('dashboard.subplans.show', $plan_id);
        }
    }

}
