<?php

namespace App\Observers;


use App\Models\UserCompany;
use Artisan;

class UserCompanyObserver
{
    public function __construct()
    {

    }

    public function created(UserCompany $company): void
    {
        //$this->updateSaferweb( $company );
    }

    public function updated(UserCompany $company): void
    {
        //$this->updateSaferweb( $company );
    }

    public function deleted(UserCompany $company): void
    {
        
    }

    public function restored(UserCompany $company): void
    {
        
    }

    public function forceDeleted(UserCompany $company): void
    {
        //
    }

    private function updateSaferweb( $company ) {

        Artisan::call('saferweb:update', ['user_id' => $company->user_id]);

    }

}
