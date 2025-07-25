<?php
namespace App\Helpers;

use App\References\DashboardReferences;

class adminSettingsHelper
{

    public static function getSidebarMenu()
    {

        $menu = DashboardReferences::sidebarMenu();

        // Set active menus
        return self::setActiveMenus($menu);
    }


    public static function setActiveMenus($menu)
    {
        $currentUrl = request()->url();
        $currentRoute = request()->route() ? request()->route()->getName() : null;

        // Set active false for all items first
        foreach ($menu as $key => $item) {
            $menu[$key]['active'] = false;
            if (isset($item['childs'])) {
                foreach ($item['childs'] as $key2 => $item2) {
                    $menu[$key]['childs'][$key2]['active'] = false;
                }
            }
        }

        // Iterate through the menu items to find the active one
        foreach ($menu as $key => $item) {
            $bestMatchLength = 0;
            $bestMatchIndex = null;

            if (isset($item['childs'])) {
                foreach ($item['childs'] as $key2 => $item2) {

                    $isExactRoute = (!empty($item2['route']) && $currentRoute === trim($item2['route']));
                    $isExactUrl = !empty($item2['url']) && $currentUrl === $item2['url'];
                    $isUrlPrefix = !empty($item2['url']) && str_starts_with($currentUrl, $item2['url']);

                    if( $isExactUrl || $isUrlPrefix ) {
                        $bestMatchLength = strlen($item2['url']);
                        $bestMatchIndex = $key2;
                        $menu[$key]['active'] = true;
                    }

                }

                // Mark only the best matching child as active
                if (isset($bestMatchIndex)) {
                    $menu[$key]['childs'][$bestMatchIndex]['active'] = true;
                }
                

            } else {
                if (!empty($item['url']) && str_starts_with($currentUrl, $item['url'])) {
                    $menu[$key]['active'] = true;
                }
            }
        }

        return $menu;
    }

}