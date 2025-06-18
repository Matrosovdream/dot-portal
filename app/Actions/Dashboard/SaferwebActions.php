<?php
namespace App\Actions\Dashboard;

use App\Models\VehicleInspection;
use App\Repositories\Vehicle\VehicleCrashesSaferwebRepo;
use App\Repositories\Vehicle\VehicleInspectionsSaferwebRepo;



class SaferwebActions {

    public function __construct(
        private VehicleCrashesSaferwebRepo $crashesRepo,
        private VehicleInspectionsSaferwebRepo $inspectionsRepo
    )
    { }

    public function inspections( $request )
    {

        $items = $this->inspectionsRepo->getAll();

        return [
            'title' => 'Inspections',
            'items' => []
        ];

    }

    public function crashes( $request )
    {

        $items = $this->crashesRepo->getAll();

        return [
            'title' => 'Crashes',
            'items' => $items
        ];
       
    }

}