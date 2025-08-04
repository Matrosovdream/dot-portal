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

        // If custom price is enabled, ignore price_per_driver
        if ( $request->has('is_custom_price') ) {
            $planData['price_per_driver'] = 0;
            $planData['is_custom_price'] = true;
        }

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

        // If custom price is enabled, ignore price_per_driver
        if ( $request->has('is_custom_price') ) {
            $planData['price_per_driver'] = 0;
            $planData['is_custom_price'] = true;
        } else {
            $planData['is_custom_price'] = false;
        }

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
            'slug' => 'required|string|max:255',
            'price_per_driver' => 'numeric|min:0',
            'drivers_amount_from' => 'required|integer|min:1',
            'drivers_amount_to' => 'required|integer|min:1|gte:drivers_amount_from',
        ];
    
        // Only validate price_per_driver if custom_price is not enabled
        if( $request->has('is_custom_price') ) {
            unset($fields['price_per_driver']);
        }
    
        // Unique slug if needed
        if ($uniqueSlug) {
            $fields['slug'] .= '|unique:subscriptions,slug,' . $request->route('plan_id');
        } else {
            $fields['slug'] .= '|unique:subscriptions,slug';
        }
    
        $request->validate($fields);
    }    

}