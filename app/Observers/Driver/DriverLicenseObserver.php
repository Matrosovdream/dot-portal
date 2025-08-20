<?php

namespace App\Observers\Driver;

use App\Models\DriverLicense;
use App\Helpers\Validation\Models\DriverValidation;



class DriverLicenseObserver
{

    public function __construct(
        protected DriverValidation $driverValidation 
    )
    { }

    public function created(DriverLicense $license): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($license->driver_id);
    }

    public function updated(DriverLicense $license): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($license->driver_id);

    }

    public function deleted(DriverLicense $license): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($license->driver_id);
    }

    public function restored(DriverLicense $license): void
    {


    }

    public function forceDeleted(DriverLicense $license): void
    {
        //
    }

}
