<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Helpers\adminSettingsHelper;

class DashboardHomeController extends Controller
{
    
    public function index()
    {

        // User role
        if( auth()->user()->isAdmin() ) {
            $route = 'dashboard.home.admin';
        } elseif( auth()->user()->isManager() ) {
            $route = 'dashboard.home.admin';
        } elseif( auth()->user()->isUser() ) {
            $route = 'dashboard.home.user';
        }

        $data = [
            'title' => 'Dashboard',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return view($route, $data);
    }

}
