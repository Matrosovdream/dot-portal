<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\SubscriptionUserActions;

class SubscriptionUserController extends Controller
{

    private $serviceAdminActions;

    public function __construct(SubscriptionUserActions $serviceAdminActions)
    {
        $this->serviceAdminActions = $serviceAdminActions;
    }

    public function index()
    {
        return view(
            'dashboard.subscription.index', 
            $this->serviceAdminActions->index()
        );
    }

    public function update($service_id, Request $request)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        $data = $this->serviceAdminActions->update($service_id, $request);
        
        return redirect()->route('dashboard.subscription.index');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        $data = $this->serviceAdminActions->store($request);

        return redirect()->route('dashboard.subscription.index');
    }

}
