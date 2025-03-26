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

        switch ($userRole) {
            case 'admin':
                $menu = new AdminMenu();
                break;
            case 'company':
                $menu = new CompanyMenu();
                break;
            case 'manager':
                $menu = new ManagerMenu();
                break;
            case 'driver':
                $menu = new DriverMenu();
                break;
            default:
                $menu = new CompanyMenu();
                break;
        }

        return $menu->getMenus()['sidebar'];

    }
}