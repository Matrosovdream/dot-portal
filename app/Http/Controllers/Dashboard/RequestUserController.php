<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\RequestUserActions;
use Illuminate\Http\Request;

class RequestUserController extends Controller
{

    private $RequestUserActions;

    public function __construct()
    {
        $this->RequestUserActions = new RequestUserActions;
    }

    public function showGroup( $groupslug )
    {
        //dd($this->RequestUserActions->showGroup(  $groupslug));
        return view(
            'dashboard.servicerequest.index', 
            $this->RequestUserActions->showGroup(  $groupslug)
        );
    }

    public function show( $groupslug, $serviceslug )
    {
        return view(
            'dashboard.servicerequest.show', 
            $this->RequestUserActions->show( $groupslug, $serviceslug )
        );
    }
    
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'nullable',
            'is_active' => 'nullable'
        ]);

        $data = $this->RequestUserActions->store($validated);
        return redirect()->route('dashboard.servicerequest.index');
    }

    public function storeRequest($groupslug, $serviceslug, Request $request)
    {
        $res = $this->RequestUserActions->storeRequest($groupslug, $serviceslug, $request);

        if( isset($res['error']) ) {
            return redirect()->back()->withErrors([ $res['errors'] ]);
        }

        return redirect()->route('dashboard.servicerequest.history.index');
    }

    public function history() {

        return view(
            'dashboard.servicerequest.history.index', 
            $this->RequestUserActions->history()
        );

    }

    public function historyShow($service_id) {

        return view(
            'dashboard.servicerequest.history.show', 
            $this->RequestUserActions->historyShow($service_id)
        );

    }

    public function historyShowPayments($request_id) {

        return view(
            'dashboard.servicerequest.history.show', 
            $this->RequestUserActions->historyShowPayments($request_id)
        );

    }

    public function historyShowPay($request_id) {

        return view(
            'dashboard.servicerequest.history.showpay', 
            $this->RequestUserActions->historyShowPay($request_id)
        );

    }

    public function historyShowPayProcess( Request $request, $request_id ) {

        $validated = $request->validate([
            'payment_method' => 'required',
        ]);

        $data = $this->RequestUserActions->historyShowPayProcess($validated, $request_id);
        
        if( isset($data['error']) ) {
            return redirect()->back()->withErrors([ $data['message'] ]);
        } else {
            return redirect()->route('dashboard.servicerequest.history.index');
        }

    }


    public function destroy($service)
    {
        $data = $this->RequestUserActions->destroy($service);
        return redirect()->route('dashboard.servicerequest.index');
    }

}
