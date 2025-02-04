<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Driver\DriverRepo;

class DriverUserActions {

    private $driverRepo;

    public function __construct()
    {
        $this->driverRepo = new DriverRepo();
    }

    public function index()
    {

        // Get drivers by user
        $drivers = $this->driverRepo->getUserDrivers( 
            auth()->user()->id, 
            $paginate = 10 
        );

        $data = [
            'title' => 'My drivers',
            'drivers' => $drivers,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}