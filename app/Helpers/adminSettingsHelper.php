<?php
namespace App\Helpers;

use App\References\DashboardReferences;

class adminSettingsHelper {

    public static function getSidebarMenu() {

        $menu = DashboardReferences::sidebarMenu();

        // Filter menu by user role
        $menu = self::filterMenuByRole($menu);

        // Set active menus
        $menu = self::setActiveMenus($menu);

        return $menu;

    }

    public static function filterMenuByRole( $menu ) {

        $user = auth()->user();
        $menu = array_filter($menu, function($item) use ($user) {
            if (isset($item['roles'])) {
                if (in_array($user->getRole()->slug, $item['roles'])) {
                    return true;
                }
            } else {
                return true;
            }
        });

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