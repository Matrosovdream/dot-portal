<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\SubRequestsActions;

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
        $res = $this->actions->update($sub_id, $request);
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
