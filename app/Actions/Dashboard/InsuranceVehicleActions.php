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

        $insurance = $this->insuranceRepo->create(
            $request,
            ['document' => 'document']
        );

    }

    public function show($id)
    {
        return [
            'title' => 'Insurance Vehicle',
            'insurance' => $this->insuranceRepo->getById($id),
            'references' => $this->getReferences(),
        ];
    }

    public function profile($id)
    {
        return [
            'title' => 'Insurance Vehicle General',
            'insurance' => $this->insuranceRepo->getById($id),
            'references' => $this->getReferences(),
        ];
    }

    public function update($request, $id)
    {
        $insurance = $this->insuranceRepo->update(
            $id,
            $request,
            ['document' => 'document']
        );
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