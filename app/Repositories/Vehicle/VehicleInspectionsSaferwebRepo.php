<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleInspectionSaferweb;


class VehicleInspectionsSaferwebRepo extends AbstractRepo
{

    protected $model;

    protected $fileRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new VehicleInspectionSaferweb();
    }

    public function syncItems($vehicle_id, $data)
    {
        if (empty($data)) return;

        foreach ($data as $item) {
            $this->sync($vehicle_id, $item);
        }
    }

    public function sync($vehicle_id, $data) 
    {

        if (empty($data)) return;

        return $this->model->updateOrCreate(
            ['vehicle_id' => $data['vehicle_id'], 'report_number' => $data['report_number']],
            $data
        );

    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'vehicle_id' => $item->vehicle_id,
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