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

        foreach ($menu as $key => $item) {
            $menu[$key]['active'] = false;
            $bestMatchLength = 0;

            if (isset($item['childs'])) {
                foreach ($item['childs'] as $key2 => $item2) {
                    $menu[$key]['childs'][$key2]['active'] = false;

                    $isExactRoute = !empty($item2['route']) && $currentRoute === $item2['route'];
                    $isUrlPrefix = !empty($item2['url']) && str_starts_with($currentUrl, $item2['url']);

                    // Prefer route match or longest URL match
                    if ($isExactRoute || ($isUrlPrefix && strlen($item2['url']) > $bestMatchLength)) {
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