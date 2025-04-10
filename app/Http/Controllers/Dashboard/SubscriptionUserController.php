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

    public function update(Request $request)
    {

        $validated = $request->validate([
            'plan' => 'required',
        ]);
        $this->serviceAdminActions->update($validated);
        
        return redirect()->back()->with('success', 'Subscription updated successfully.');
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

    public function destroyCard( $card_id ) {

        $this->serviceAdminActions->destroyCard($card_id);
        return redirect()->back()->with('success', 'Card deleted successfully.');

    }

}
