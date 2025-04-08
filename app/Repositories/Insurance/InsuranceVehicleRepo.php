<?php
namespace App\Repositories\Insurance;

use App\Repositories\AbstractRepo;
use App\Models\InsuranceVehicle;
use App\Repositories\File\FileRepo;
use App\Repositories\User\UserRepo;


class InsuranceVehicleRepo extends AbstractRepo
{

    protected $model;

    protected $fields = ['file', 'company'];
    protected $fileRepo;
    protected $userRepo;

    public function __construct()
    {
        $this->model = new InsuranceVehicle();

        $this->fileRepo = new FileRepo();
        $this->userRepo = new UserRepo;
    }

    public function updateVehicles($insuranceId, $vehicleIds)
    {
        $insurance = $this->model->find($insuranceId);
        if ($insurance) {
            $insurance->vehicles()->sync($vehicleIds);
        }
    }

    public function mapItem($item)
    {

        if( empty($item) ) return null;

        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'number' => $item->number,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'file' => $this->fileRepo->mapItem($item->file),
            'company' => $this->userRepo->mapItem($item->company),
            'user_id' => $item->user_id,
            'Model' => $item
        ];
        return $res;
    }

}