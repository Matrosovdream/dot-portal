<?php

namespace App\Actions\Dashboard;

use Illuminate\Http\Request;
use App\Repositories\File\FileRepo;
use App\Helpers\adminSettingsHelper;


class DocumentActions {

    private $fileRepo;

    public function __construct()
    {

        $this->fileRepo = new FileRepo();

    }

    public function index()
    {

        $filter = [];
        $filter['user_id'] = auth()->user()->id;
        if( request()->has('q') ) {
            $filter['filename'] = '%' . request()->input('q') . '%';
        }

        $data = [
            'title' => 'Documents',
            'documents' => $this->fileRepo->getAll($filter, 30),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}