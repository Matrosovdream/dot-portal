<?php
namespace App\Actions\Dashboard;

use App\Models\Service;

class HomeManagerActions {

    public function __construct()
    {
        // 
    }

    public function index()
    {
        $data = [
            'title' => 'Services list',
            'services' => Service::paginate(10)
        ];

        return $data;
    }

}