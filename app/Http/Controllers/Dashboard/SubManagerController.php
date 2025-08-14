<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\SubManagerActions;
use App\Http\Requests\Dashboard\SubManager\StoreRequest;

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

    public function store(StoreRequest $request)
    {
        $res = $this->actions->store($request);

        if( $res ) {
            return redirect()->route('dashboard.usersubscriptions.show', $res['sub']['id']);
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to create subscription.']);
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $res = $this->actions->userStore($sub_id, $validated);
        if( $res ) {
            return redirect()->back();
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
            return redirect()->back();
        }
    }

    public function sendOnceLogin($sub_id, Request $request)
    {
        $res = $this->actions->sendOnceLogin($sub_id); 
        if( $res ) {
            return redirect()->back()->with('success', 'One-time login link sent successfully.');
        }
    }

}
