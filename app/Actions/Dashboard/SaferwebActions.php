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

        $filter = [];

        // Filter by search form
        $filter = ['company_id' => auth()->user()->company->id];

        // Filter by search form
        if( request()->has('q') && !empty(request()->input('q')) ) {

            $items = $this->inspectionsRepo->modelSearch( request()->input('q'), false);
            $item_ids = $items->pluck('id')->toArray();

            $filter['id'] = $item_ids; 
            
        }

        $items = $this->inspectionsRepo->getAll($filter, $paginate = 30);

        return [
            'title' => 'Inspections',
            'items' => $items
        ];

    }

    public function crashes( $request )
    {

        $filter = [];

        // Filter by search form
        $filter = ['company_id' => auth()->user()->company->id];

        // Filter by search form
        if( request()->has('q') && !empty(request()->input('q')) ) {

            $items = $this->crashesRepo->modelSearch( request()->input('q'), false);
            $item_ids = $items->pluck('id')->toArray();

            $filter['id'] = $item_ids; 
            
        }

        $items = $this->crashesRepo->getAll($filter, $paginate = 30);

        return [
            'title' => 'Crashes',
            'items' => $items
        ];
       
    }

}