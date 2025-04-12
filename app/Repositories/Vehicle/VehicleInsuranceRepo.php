<?php
namespace App\Repositories\Vehicle;

use App\Repositories\AbstractRepo;
use App\Models\VehicleInsuranceLink;
use App\Repositories\Insurance\InsuranceVehicleRepo;


class VehicleInsuranceRepo extends AbstractRepo
{

    protected $model;

    protected $insuranceRepo;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new VehicleInsuranceLink();

        $this->insuranceRepo = new InsuranceVehicleRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'vehicle_id' => $item->vehicle_id,
            'insurance_id' => $item->insurance_id,
            'insurance' => $this->insuranceRepo->mapItem($item->insurance),
            'Model' => $item

        ];
        return $res;
    }

}