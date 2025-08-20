<?php

namespace App\Observers\Vehicle;

use App\Models\VehicleInsuranceLink;
use App\Helpers\Validation\Models\VehicleValidation;


class VehicleInsuranceObserver
{

    public function __construct(
        protected VehicleValidation $validation
    )
    { }

    public function created(VehicleInsuranceLink $insurance): void
    {
        // Validate driver data and update events if necessary
        $this->validation->updateUserTasks($insurance->vehicle_id);
    }

    public function updated(VehicleInsuranceLink $insurance): void
    {

        // Validate driver data and update events if necessary
        $this->validation->updateUserTasks($insurance->vehicle_id);

    }

    public function deleted(VehicleInsuranceLink $insurance): void
    {
        // Validate driver data and update events if necessary
        $this->validation->updateUserTasks($insurance->vehicle_id);
    }

    public function restored(VehicleInsuranceLink $insurance): void
    {


    }

    public function forceDeleted(VehicleInsuranceLink $insurance): void
    {
        //
    }

}
