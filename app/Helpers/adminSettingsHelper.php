<?php
namespace App\Helpers;

use App\References\DashboardReferences;

class adminSettingsHelper
{

    public static function getSidebarMenu()
    {

        $menu = DashboardReferences::sidebarMenu();

        // Set active menus
        $menu = self::setActiveMenus($menu);

        //dd($menu);

        return $menu;

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

            if (isset($item['childs'])) {
                foreach ($item['childs'] as $key2 => $item2) {

                    $isExactRoute = !empty($item2['route']) && $currentRoute === $item2['route'];
                    $isExactUrl = !empty($item2['url']) && $currentUrl === $item2['url'];
                    $isUrlPrefix = !empty($item2['url']) && str_starts_with($currentUrl, $item2['url']);

                    if( $isExactUrl ) {
                        $bestMatchLength = strlen($item2['url']);
                        $bestMatchIndex = $key2;
                        $menu[$key]['active'] = true;
                        break;
                    } elseif( $isUrlPrefix ) {
                        $bestMatchLength = strlen($item2['url']);
                        $bestMatchIndex = $key2;
                        $menu[$key]['active'] = true;
                        break;
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

        if( request()->has('lggg') ) {
            dd($menu[5]['childs']);
        }

        return $menu;
    }




}