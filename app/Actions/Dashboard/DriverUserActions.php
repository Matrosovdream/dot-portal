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

    public function show($driver_id)
    {
        $driver = $this->driverRepo->getByID($driver_id);

        $data = [
            'title' => 'Driver details',
            'driver' => $driver,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function update($driver_id, $request)
    {
        $data = $this->driverRepo->update($driver_id, $request);

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create driver',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function store($request)
    {
        $data = $this->driverRepo->create($request);

        return $data;
    }

    public function destroy($driver_id)
    {
        $data = $this->driverRepo->delete($driver_id);

        return $data;
    }

}