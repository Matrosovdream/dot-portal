<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleInspectionSaferweb;


class VehicleInspectionsSaferwebRepo extends AbstractRepo
{

    public $model;

    protected $fileRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new VehicleInspectionSaferweb();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'vehicle_id' => $item->vehicle_id,
            'unit_vin' => $item->unit_vin,
            'company_id' => $item->company_id,
            'dot_number' => $item->dot_number,
            'unique_id'=> $item->unique_id,
            'report_date' => $item->report_date,
            'report_number' => $item->report_number,
            'report_sequence_number' => $item->report_sequence_number,
            'inspection_level' => $item->inspection_level,
            'report_state' => $item->report_state,
            'report_state_id' => $item->report_state_id,
            'api_data' => $item->api_data,
            'Model' => $item

        ];
        return $res;
    }

}