<?php

namespace App\Observers;

use App\Models\Vehicle;
use App\Repositories\User\UserRepo;


class VehicleObserver
{

    public function __construct(
        protected UserRepo $userRepo
    )
    {

    }


    public function created( Vehicle $vehicle ): void
    {

        
    }


    public function updated(Vehicle $vehicle): void
    {
        $this->updateSaferwebData($vehicle);
    }


    public function deleted( Vehicle $vehicle ): void
    {
        //
    }

    private function updateSaferwebData( Vehicle $vehicle ): void
    {

        /*
        $userData = $this->userRepo->getByID($user->id);
        dd($userData['Model']->company->id ?? null);

        $companyId = $user->company->id ?? null;
        if( !$companyId ) { return; }
            dd($companyId);
        
        // Company Inspections
        UpdateCompanyInspections::dispatch($companyId)
            ->delay(now()->addSeconds($delay));   

        // Company Crashes
        UpdateCompanyCrashes::dispatch($companyId)
            ->delay(now()->addSeconds($delay));    
        */    

    }

}
