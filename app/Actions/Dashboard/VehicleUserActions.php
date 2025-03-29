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

        $vehicles = $this->vehicleRepo->getAll( [], $paginate = 10 );

        $data = [
            'title' => 'My vehicles',
            'vehicles' => $vehicles,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function show($vehicle_id)
    {
        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Vehicle details',
            'vehicle' => $vehicle,
            'references' => $this->vehicleRepo->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function update($vehicle_id, $request)
    {
        $data = $this->vehicleRepo->update($vehicle_id, $request);

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create vehicle',
            'references' => $this->vehicleRepo->getReferences(),
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

    public function destroy($vehicle_id)
    {
        $data = $this->vehicleRepo->delete($vehicle_id);

        return $data;
    }

}