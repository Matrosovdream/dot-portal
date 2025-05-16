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

    public function updateSubscription(Request $request)
    {

        $validated = $request->validate([
            'plan' => 'required',
        ]);
        $this->serviceAdminActions->updateSubscription($validated);
        
        return redirect()->back()->with('success', 'Subscription updated successfully.');
    }

    public function cancelSubscription(Request $request)
    {
        $this->serviceAdminActions->cancelSubscription($request);
        return redirect()->back()->with('success', 'Subscription cancelled successfully.');
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

    public function storeCard( Request $request ) {

        $validated = $request->validate([
            'card_number' => 'required',
            'card_expiry_month' => 'required',
            'card_expiry_year' => 'required',
            'card_cvv' => 'required',
            'card_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $this->serviceAdminActions->storeCard($validated);

        return redirect()->back()->with('success', 'Card added successfully.');

    }

    public function makePrimaryCard( $card_id ) {

        $this->serviceAdminActions->makePrimaryCard($card_id);
        return redirect()->back()->with('success', 'Card set as primary successfully.');

    }

}
