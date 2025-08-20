<?php

namespace App\Observers\Driver;

use App\Models\Driver;
use App\Helpers\Validation\Models\DriverValidation;


class DriverObserver
{

    public function __construct(
        protected DriverValidation $driverValidation 
    )
    { }

    public function created(Driver $driver): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($driver->id);
    }

    public function updated(Driver $driver): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($driver->id);
    }

    public function deleted(Driver $driver): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($driver->id);
    }

    public function restored(Driver $driver): void
    {


    }

    public function forceDeleted(Driver $driver): void
    {
        //
    }

}
