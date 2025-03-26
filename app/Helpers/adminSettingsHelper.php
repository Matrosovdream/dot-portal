<?php
namespace App\Helpers;

use App\References\DashboardReferences;

class adminSettingsHelper {

    public static function getSidebarMenu() {

        $menu = DashboardReferences::sidebarMenu();

        // Set active menus
        $menu = self::setActiveMenus($menu);

        //dd($menu);

        return $menu;

    }


    public static function setActiveMenus( $menu ) {

        // Lets mark active menus using routes data
        foreach ($menu as $key => $item) {
            if (isset($item['childs'])) {
                foreach ($item['childs'] as $key2 => $item2) {
                    if ( strpos(request()->url(), $item2['url']) !== false ) {
                        $menu[$key]['active'] = true;
                        $menu[$key]['childs'][$key2]['active'] = true;
                    } else {
                        $menu[$key]['active'] = true;
                        $menu[$key]['childs'][$key2]['active'] = false;
                    }
                }
            } else {
                if ( strpos(request()->url(), $item['url']) !== false ) {
                    $menu[$key]['active'] = true;
                } else {
                    $menu[$key]['active'] = false;
                }
            }
        }

        return $menu;

    }

}