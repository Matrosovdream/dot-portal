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

        $items = $this->inspectionsRepo->getAll(
            [
                'company_id' => $request->user()->company->id ?? null,
            ],
            $paginate = 30,
            $sort = ['report_date' => 'desc', 'unit_vin' => 'asc'],
        );

        return [
            'title' => 'Inspections',
            'items' => $items
        ];

    }

    public function crashes( $request )
    {

        $items = $this->crashesRepo->getAll(
            [
                'company_id' => $request->user()->company->id ?? null,
            ]
        );

        return [
            'title' => 'Crashes',
            'items' => $items
        ];
       
    }

}