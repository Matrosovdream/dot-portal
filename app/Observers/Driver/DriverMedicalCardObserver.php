<?php

namespace App\Observers\Driver;

use App\Models\DriverMedicalCard;
use App\Helpers\Validation\Models\DriverValidation;



class DriverMedicalCardObserver
{

    public function __construct(
        protected DriverValidation $driverValidation 
    )
    { }

    public function created(DriverMedicalCard $card): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($card->driver_id);
    }

    public function updated(DriverMedicalCard $card): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($card->driver_id);

    }

    public function deleted(DriverMedicalCard $card): void
    {
        // Validate driver data and update events if necessary
        $this->driverValidation->updateUserTasks($card->driver_id);
    }

    public function restored(DriverMedicalCard $card): void
    {


    }

    public function forceDeleted(DriverMedicalCard $card): void
    {
        //
    }

}
