<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\References\RefDriverTypeRepo;

class DriverUserActions {

    private $driverRepo;
    private $refDriverTypeRepo;

    public function __construct()
    {
        $this->driverRepo = new DriverRepo();
        $this->refDriverTypeRepo = new RefDriverTypeRepo();
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
            'references' => $this->getReferences(),
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
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        //dd($data);

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

    public function getReferences()
    {
        $references = [
            'driverType' => $this->refDriverTypeRepo->getAll([], $paginate=100),
        ];

        return $references;
    }

}