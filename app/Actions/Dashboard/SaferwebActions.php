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

        $filter = $this->prepareSearch($this->inspectionsRepo);

        return [
            'title' => 'Inspections',
            'items' => $this->inspectionsRepo->getAll($filter, $paginate = 30)
        ];

    }

    public function inspectionsShow( $inspection_id, $request )
    {
dd($this->inspectionsRepo->getById($inspection_id));
        return [
            'title' => 'Inspection Details',
            'inspection' => $this->inspectionsRepo->getById($inspection_id),
        ];
    
    }

    public function crashes( $request )
    {

        $filter = $this->prepareSearch($this->crashesRepo);

        return [
            'title' => 'Crashes',
            'items' => $this->crashesRepo->getAll($filter, $paginate = 30)
        ];
       
    }

    public function crashesShow( $crash_id, $request )
    {

        return [
            'title' => 'Crash Details',
            'crash' => $this->crashesRepo->getById($crash_id),
        ];
    
    }

    public function prepareSearch( $model ) {

        // Filter by search form
        $filter = ['company_id' => auth()->user()->company->id];

        // Filter by common search
        if( request()->has('q') && !empty(request()->input('q')) ) {

            $items = $model->modelSearch( request()->input('q'), false);
            $item_ids = $items->pluck('id')->toArray();

            $filter['id'] = $item_ids; 
            
        }

        // Filter by date range From
        if( request()->has('date_from') && !empty(request()->input('date_from')) ) {
            $filter['report_date>='] = request()->input('date_from');
        }

        // Filter by date range To
        if( request()->has('date_to') && !empty(request()->input('date_to')) ) {
            $filter['report_date<='] = request()->input('date_to');
        }

        return $filter;

    }

}