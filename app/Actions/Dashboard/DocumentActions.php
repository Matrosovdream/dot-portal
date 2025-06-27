<?php

namespace App\Actions\Dashboard;

use Illuminate\Http\Request;
use App\Repositories\File\FileRepo;
use App\Helpers\adminSettingsHelper;
use App\Repositories\Driver\DriverDocumentRepo;
use App\Repositories\Driver\DriverRepo;


class DocumentActions {

    private $fileRepo;
    private $driverDocumentRepo;
    private $driverRepo;

    public function __construct()
    {

        $this->fileRepo = new FileRepo();
        $this->driverDocumentRepo = new DriverDocumentRepo();
        $this->driverRepo = new DriverRepo();

    }

    public function index()
    {

        $filter = [];
        $user = auth()->user();

        if( $user->isDriver() ) {

            $driver = $this->driverRepo->getByUserId($user->id);

            if( $driver ) {
                $files = $this->driverDocumentRepo->getAll(['driver_id' => $driver['id']]);
                $file_ids = $files['Model']->pluck('file_id')->toArray();
            }

            if( !empty($file_ids) ) {
                $filter['id'] = $file_ids;
            }

        } else {

            $filter['user_id'] = $user->id;

        }

        // Filter by search form
        if( request()->has('q') ) {

            $modelSearch = $this->fileRepo->modelSearch( request()->input('q') );

            if( isset($modelSearch['Model']) && !empty($modelSearch['Model']) ) {
                $filter['id'] = $modelSearch['Model']->pluck('id')->toArray();
            }
        }

        $data = [
            'title' => 'Documents',
            'documents' => $this->fileRepo->getAll($filter, 30),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}