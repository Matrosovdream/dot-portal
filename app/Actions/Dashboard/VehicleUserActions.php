<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Vehicle\VehicleRepo;


class VehicleUserActions {

    private $vehicleRepo;


    public function __construct()
    {
        $this->vehicleRepo = new VehicleRepo();

    }

    public function index()
    {

        // Get drivers by user
        $drivers = $this->vehicleRepo->getAll( [], $paginate = 10 );

        $data = [
            'title' => 'My drivers',
            'drivers' => $drivers,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function show($driver_id)
    {
        $driver = $this->vehicleRepo->getByID($driver_id);

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
        $data = $this->vehicleRepo->update($driver_id, $request);

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

        $data = $this->vehicleRepo->create($request);

        return $data;
    }

    public function destroy($driver_id)
    {
        $data = $this->vehicleRepo->delete($driver_id);

        return $data;
    }

    public function getReferences()
    {
        $references = [
            'driverType' => $this->refDriverTypeRepo->getAll([], $paginate=100),
            'state' => $this->refStateRepo->getAll([], $paginate=100),
            'licenseEndrs' => $this->refDriverLicenseEndrsRepo->getAll([], $paginate=100),
        ];

        return $references;
    }

}