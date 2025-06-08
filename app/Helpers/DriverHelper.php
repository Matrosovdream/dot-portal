<?php 

class DriverHelper {

    public function terminateDriver($driver_id)
    {
        $driverRepo = app('App\Repositories\Driver\DriverRepo');
        $userRepo = app('App\Repositories\User\UserRepo');

        // Get the driver by ID
        $driver = $driverRepo->getByID($driver_id);

        // Check if the driver exists
        if (!$driver) {
            return ['error' => 'Driver not found'];
        }

        // Terminate the driver
        $result = $driverRepo->terminate($driver_id);

        // If termination was successful, update the user status
        if ($result) {
            $userRepo->updateStatus($driver->user_id, 'terminated');
            return ['success' => 'Driver terminated successfully'];
        }

        return ['error' => 'Failed to terminate driver'];
    }

}