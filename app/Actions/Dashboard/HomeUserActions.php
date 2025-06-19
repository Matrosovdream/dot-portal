<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
use App\Repositories\User\UserRepo;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\Vehicle\VehicleRepo;
use App\Repositories\Insurance\InsuranceVehicleRepo;

class HomeUserActions {

    private $userRepo;
    private $driverRepo;
    private $vehicleRepo;
    private $insuranceRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo;
        $this->driverRepo = new DriverRepo;
        $this->vehicleRepo = new VehicleRepo;
        $this->insuranceRepo = new InsuranceVehicleRepo;

    }

    public function index()
    {
        $data = [
            'title' => 'Services list',
            'user' => $this->userRepo->getById( auth()->user()->id ),
            'stats' => $this->getStats(),
            'services' => Service::paginate(10)
        ];

        return $data;
    }

    public function getStats() {

        $stats = [
            'drivers' => $this->driverRepo->getCompanyStats( auth()->user()->id ),
            'vehicles' => $this->vehicleRepo->getCompanyStats( auth()->user()->id ),
            'insurances' => $this->insuranceRepo->getCompanyStats( auth()->user()->id ),
        ];

        return $stats;

    }

}