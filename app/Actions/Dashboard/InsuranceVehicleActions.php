<?php

namespace App\Actions\Dashboard;

use Illuminate\Http\Request;
use App\Repositories\File\FileRepo;
use App\Helpers\adminSettingsHelper;
use App\Repositories\Driver\DriverDocumentRepo;
use App\Repositories\Insurance\InsuranceVehicleRepo;


class InsuranceVehicleActions {

    private $insurenceRepo;

    public function __construct()
    {

        $this->insurenceRepo = new InsuranceVehicleRepo();

    }

    public function index()
    {
        $items = $this->insurenceRepo->getAll();

        return [
            'title' => 'Insurance Vehicles',
            'items' => $items,
        ];
        
    }

    public function store(Request $request)
    {
        $this->insurenceRepo->create($request);
    }

    public function edit($id)
    {
        return $this->insurenceRepo->getById($id);
    }

    public function update(Request $request, $id)
    {
        $this->insurenceRepo->update($request, $id);
    }

    public function destroy($id)
    {
        $this->insurenceRepo->delete($id);
    }

}