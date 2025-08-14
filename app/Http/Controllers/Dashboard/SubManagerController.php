<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\SubManagerActions;

class SubManagerController extends Controller
{

    public function __construct(
        private SubManagerActions $actions)
    {

    }

    public function index()
    {
        return view('dashboard.submanager.index', $this->actions->index());
    }

    public function create()
    {
        return view('dashboard.submanager.create', $this->actions->create());
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'custom_price' => 'required|numeric',
            'status_id' => 'required|integer',
        ]);

        $res = $this->actions->store($validated);
        if( $res ) {
            return redirect()->route('dashboard.submanager.show', $res['req_id']);
        }
    }

    public function show($sub_id)
    {
        return view('dashboard.submanager.show', $this->actions->show($sub_id));
    }

    public function update($sub_id, Request $request)
    {

        $validated = $request->validate([
            'status' => 'required|string',
            'subscription_id' => 'required|integer',
            'drivers_number' => 'required|integer',
            'price_per_driver' => 'required|numeric',
        ]);

        $res = $this->actions->update($sub_id, $validated);
        if( $res ) { return redirect()->back(); }
    }

    public function userStore($sub_id, Request $request)
    {
        $validated = $request->validate([
            //'custom_price' => 'required|numeric',
        ]);

        $res = $this->actions->userStore($sub_id, $validated);
        if( $res ) {
            return redirect()->route('dashboard.submanager.show', $res['sub_id']);
        }
    }

    public function companyStore($sub_id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dot_number' => 'required|string|max:255',
            'mc_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $res = $this->actions->companyStore($sub_id, $validated);
        if( $res ) {
            return redirect()->route('dashboard.submanager.show', $sub_id);
        }
    }

    public function sendEmail($sub_id)
    {
        $res = $this->actions->sendEmail($sub_id); 
        if( $res ) {
            return redirect()->route('dashboard.submanager.show', $sub_id);
        }
    }

}
