<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Helpers\adminSettingsHelper;


class DashboardProfileController {

    public function profile()
    {
        $data = [
            'title' => 'Profile',
            'user' => auth()->user(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return view('dashboard.profile.index', $data);
    }

    public function update()
    {
        $data = [
            'title' => 'Update profile',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return view('dashboard.profile.update', $data);
    }

    public function updatePassword()
    {
        $data = [
            'title' => 'Update password',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return view('dashboard.profile.update_password', $data);
    }

}