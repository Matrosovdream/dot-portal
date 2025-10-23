<?php

namespace App\References;

use App\References\Menu\AdminMenu;
use App\References\Menu\ManagerMenu;
use App\References\Menu\CompanyMenu;
use App\References\Menu\DriverMenu;


class DashboardReferences
{

    public static function sidebarMenu()
    {

        $userRole = auth()->user()->getRole()->slug;

        $menuMap = [
            'admin'   => AdminMenu::class,
            'company' => CompanyMenu::class,
            'manager' => ManagerMenu::class,
            'driver'  => DriverMenu::class,
        ];
        
        // Default to CompanyMenu if role not found
        $menuClass = $menuMap[$userRole] ?? CompanyMenu::class;
        $menu = new $menuClass();        

        return $menu->getMenus()['sidebar'];

    }
}