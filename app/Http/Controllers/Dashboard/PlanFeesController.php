<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\PlanFeesActions;

class PlanFeesController extends Controller
{

    public function __construct(
        private PlanFeesActions $actions)
    {

    }

    public function index()
    {
        return view('dashboard.planfees.index', $this->actions->index());
    }

    public function show($fee_id)
    {
        return view('dashboard.planfees.show', $this->actions->show($fee_id));
    }

    public function update($fee_id, Request $request)
    {
        $res = $this->actions->update($fee_id, $request);
        if( $res ) {
            return redirect()->route('dashboard.planfees.show', ['fee_id' => $fee_id]);
        }
    }

}
