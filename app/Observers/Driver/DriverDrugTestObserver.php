<?php

namespace App\Observers\Driver;

use App\Models\DriverDrugTest;
use App\Helpers\Validation\Models\DriverValidation;



class DriverDrugTestObserver
{

    public function __construct(
        protected DriverValidation $driverValidation 
    )
    { }

    public function created(DriverDrugTest $test): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($test->driver_id);
    }

    public function updated(DriverDrugTest $test): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($test->driver_id);

    }

    public function deleted(DriverDrugTest $test): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($test->driver_id);
    }

    public function restored(DriverDrugTest $test): void
    {


    }

    public function forceDeleted(DriverDrugTest $test): void
    {
        //
    }

}
