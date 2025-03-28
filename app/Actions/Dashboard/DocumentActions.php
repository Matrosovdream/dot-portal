<?php

namespace App\Actions\Dashboard;

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

        $data = [
            'title' => 'Documents',
            'documents' => $this->fileRepo->getAll(['user_id' => auth()->user()->id], 30),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

 

}