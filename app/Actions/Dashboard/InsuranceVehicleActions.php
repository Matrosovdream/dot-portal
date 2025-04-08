<?php

namespace App\Actions\Dashboard;

use Illuminate\Http\Request;

use App\Repositories\Insurance\InsuranceVehicleRepo;
use App\Repositories\Vehicle\VehicleRepo;


class InsuranceVehicleActions {

    private $insuranceRepo;
    private $vehicleRepo;

    public function __construct()
    {

        $this->insuranceRepo = new InsuranceVehicleRepo();
        $this->vehicleRepo = new VehicleRepo();

    }

    public function index()
    {
        $items = $this->insuranceRepo->getAll([], $paginate = 30);

        return [
            'title' => 'Insurance Vehicles',
            'insurances' => $items,
        ];

    }

    public function create()
    {
        $data = [
            'title' => 'Create Insurance Vehicle',
            'references' => $this->getReferences(),
            'companies' => [],
        ];

        return $data;
    }

    public function store($request)
    {

        // Set current company as user ID
        $request['company_id'] = auth()->user()->id;

        $insurance = $this->insuranceRepo->create($request);

        // Attach vehicles to insurance
        if( !empty( $request['vehicle_ids'] ) ) {
            $this->insuranceRepo->updateVehicles($insurance['id'], $request['vehicle_ids']);
        }

    }

    public function edit($id)
    {
        return $this->insuranceRepo->getById($id);
    }

    public function update(Request $request, $id)
    {
        $this->insuranceRepo->update($request, $id);
    }

    public function destroy($id)
    {
        $this->insuranceRepo->delete($id);
    }

    public function getReferences()
    {
        $references = [
            'vehicle' => $this->vehicleRepo->getAll(['company_id' => 1]),
        ];

        return $references;
    }

}