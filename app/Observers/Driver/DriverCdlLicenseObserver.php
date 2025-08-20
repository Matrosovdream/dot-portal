<?php

namespace App\Observers\Driver;

use App\Models\DriverCdlLicense;
use App\Helpers\Validation\Models\DriverValidation;



class DriverCdlLicenseObserver
{

    public function __construct(
        protected DriverValidation $driverValidation 
    )
    { }

    public function created(DriverCdlLicense $license): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($license->driver_id);
    }

    public function updated(DriverCdlLicense $license): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($license->driver_id);

    }

    public function deleted(DriverCdlLicense $license): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($license->driver_id);
    }

    public function restored(DriverCdlLicense $license): void
    {


    }

    public function forceDeleted(DriverCdlLicense $license): void
    {
        //
    }

}
