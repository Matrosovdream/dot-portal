<?php 

namespace App\Helpers;

use App\Repositories\Driver\DriverRepo;
use App\Repositories\User\UserRepo;

class DriverHelper {

    public function terminate($driver_id)
    {
        $driverRepo = app(DriverRepo::class);

        // If termination was successful, update the user status
        $driverRepo->updateStatus($driver_id, 3);

    }

}