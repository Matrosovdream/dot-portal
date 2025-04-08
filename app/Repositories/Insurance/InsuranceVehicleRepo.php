<?php
namespace App\Repositories\Insurance;

use App\Repositories\AbstractRepo;
use App\Models\InsuranceVehicle;
use App\Repositories\File\FileRepo;
use App\Repositories\User\UserRepo;
use App\Mixins\File\FileStorage;


class InsuranceVehicleRepo extends AbstractRepo
{

    protected $model;

    protected $fields = ['file', 'company'];
    protected $fileRepo;
    protected $userRepo;
    protected $fileStorage;

    public function __construct()
    {
        $this->model = new InsuranceVehicle();

        $this->fileRepo = new FileRepo();
        $this->userRepo = new UserRepo;

        $this->fileStorage = new FileStorage();
    }

    public function create( $data, $files = [] ) {

        $insurance = $this->model->create($data);

        // Upload files
        if( isset($files['document']) ) {
            
            $this->uploadDocument(
                $insurance['id'],
                $files['document'],
                ['insurance', 'document']
            );

        }

        return $insurance;

    }

    private function uploadDocument( $insurance_id, $filename, $tags = [] ) {

        $file = $this->fileStorage->uploadFile(
            $filename, 
            'insurance/' . $insurance_id,
            'local',
            ['tags' => $tags]
        );

        if( isset($file['file']['id']) ) {
            $this->update(
                $insurance_id,
                [
                    'file_id' => $file['file']['id'],
                ]
            );
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