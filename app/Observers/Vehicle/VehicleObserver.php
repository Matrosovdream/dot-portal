<?php

namespace App\Observers\Vehicle;

use App\Models\Vehicle;
use App\Helpers\Validation\Models\VehicleValidation;


class VehicleObserver
{

    public function __construct(
        protected VehicleValidation $validation
    )
    { }

    public function created(Vehicle $vehicle): void
    {
        // Validate driver data and update events if necessary
        $this->validation->updateUserTasks($vehicle->id);
    }

    public function updated(Vehicle $vehicle): void
    {

        // Validate driver data and update events if necessary
        $this->validation->updateUserTasks($vehicle->id);

    }

    public function deleted(Vehicle $vehicle): void
    {
        // Validate driver data and update events if necessary
        $this->validation->updateUserTasks($vehicle->id);
    }

    public function restored(Vehicle $vehicle): void
    {


    }

    public function forceDeleted(Vehicle $vehicle): void
    {
        //
    }

}
