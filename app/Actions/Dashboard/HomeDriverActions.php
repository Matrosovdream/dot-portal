<?php
namespace App\Actions\Dashboard;

use App\Models\Service;

class HomeDriverActions {

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