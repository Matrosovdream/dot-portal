<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\Vehicle\VehicleRepo;

class HomeUserActions {

    private $driverRepo;
    private $vehicleRepo;

    public function __construct()
    {
        $this->driverRepo = new DriverRepo;
        $this->vehicleRepo = new VehicleRepo;

    }

    public function index()
    {
        $data = [
            'title' => 'Services list',
            'stats' => $this->getStats(),
            'services' => Service::paginate(10)
        ];

        return $data;
    }

    public function getStats() {

        $stats = [
            'drivers' => $this->driverRepo->getCompanyStats( auth()->user()->id ),
            'vehicles' => $this->vehicleRepo->getCompanyStats( auth()->user()->id ),
        ];
//dd($stats);
        return $stats;

    }

}