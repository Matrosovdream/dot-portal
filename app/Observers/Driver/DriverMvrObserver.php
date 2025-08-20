<?php

namespace App\Observers\Driver;

use App\Models\DriverMvr;
use App\Helpers\Validation\Models\DriverValidation;



class DriverMvrObserver
{

    public function __construct(
        protected DriverValidation $driverValidation 
    )
    { }

    public function created(DriverMvr $mvr): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($mvr->driver_id);
    }

    public function updated(DriverMvr $mvr): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($mvr->driver_id);
    }

    public function deleted(DriverMvr $mvr): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($mvr->driver_id);
    }

    public function restored(DriverMvr $mvr): void
    {


    }

    public function forceDeleted(DriverMvr $mvr): void
    {
        //
    }

}
