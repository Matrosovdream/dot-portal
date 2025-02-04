<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
use App\Helpers\adminSettingsHelper;

class HomeAdminActions {

    public function __construct()
    {
        // 
    }

    public function index()
    {
        $data = [
            'title' => 'Services list',
            'services' => Service::paginate(10),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}