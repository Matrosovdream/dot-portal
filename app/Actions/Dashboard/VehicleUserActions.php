<?php
namespace App\Actions\Dashboard;

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
            'vehicles' => $vehicles
        ];

        return $data;
    }

    public function show($vehicle_id)
    {
        $vehicle = $this->vehicleRepo->getByID($vehicle_id);

        $data = [
            'title' => 'Vehicle details',
            'vehicle' => $vehicle,
            'references' => $this->vehicleRepo->getReferences()
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
            'references' => $this->vehicleRepo->getReferences()
        ];

        //dd($data);

        return $data;
    }

    public function store($request)
    {
        return $this->vehicleRepo->create($request);
    }

    public function destroy($vehicle_id)
    {
        return $this->vehicleRepo->delete($vehicle_id);
    }

}