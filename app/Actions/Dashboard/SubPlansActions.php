<?php
namespace App\Actions\Dashboard;

use App\Repositories\Subscription\SubscriptionRepo;


class SubPlansActions {

    private $subRepo;

    public function __construct()
    {
        $this->subRepo = new SubscriptionRepo();

    }

    public function index()
    {

        $data = [
            'title' => 'Subscription Plans',
            'plans' => $this->subRepo->getAll(),
        ];
        
        return $data;
    }

    public function create()
    {
        return [
            'title' => 'Create New Plan',
        ];
    }
    
    public function store($request)
    {
        $this->validateStoreRequest($request);

        $planData = $request->only([
            'name',
            'slug',
            'price_per_driver',
            'drivers_amount_from',
            'drivers_amount_to',
            'short_description',
        ]);

        $planData['duration'] = 'monthly'; 
        $this->subRepo->create($planData);

        return true;
    }

    public function show( $plan_id )
    {
        $plan = $this->subRepo->getByID( $plan_id );

        if( !$plan ) {
            abort(404, 'Plan not found');
        }

        return [
            'title' => 'Plan Details #' . $plan['id'],
            'plan' => $plan,
        ];
    }

    public function update($plan_id, $request)
    {
        $plan = $this->subRepo->getByID($plan_id);

        if (!$plan) {
            abort(404, 'Plan not found');
        }

        $this->validateStoreRequest($request);

        $planData = $request->only([
            'name',
            'slug',
            'price_per_driver',
            'drivers_amount_from',
            'drivers_amount_to',
            'short_description',
        ]);
        $this->subRepo->update($plan_id, $planData);

        return [
            'title' => 'Initial plan Updated #' . $plan['id'],
            'plan' => $this->subRepo->getByID($plan_id),
        ];
    }

    private function validateStoreRequest($request, $uniqueSlug = true)
    {

        $fields = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscriptions,slug',
            'price_per_driver' => 'required|numeric|min:0',
            'drivers_amount_from' => 'required|integer|min:1',
            'drivers_amount_to' => 'required|integer|min:1|gte:drivers_amount_from',
        ];

        if ($uniqueSlug) {
            $fields['slug'] .= ',' . $request->route('plan_id');
        }

        $request->validate( $fields );
    }   

}