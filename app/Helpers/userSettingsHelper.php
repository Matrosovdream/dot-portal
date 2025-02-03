<?php
namespace App\Helpers;

class userSettingsHelper {

    public static function getTopMenu() {

        $menu = array(
            array(
                'title' => __('Main'),
                'url' => '/',
            ),
            array(
                'title' => __('Articles'),
                'url' => '/articles',
            )
            
            /*
            array(
                'title' => 'Content',
                'url' => '',
                'icon' => 'ki-file',
                'childs' => array(
                    array(
                        'title' => 'Articles',
                        'url' => route('dashboard.articles.index'),
                    ),
                    array(
                        'title' => 'Countries',
                        'url' => route('dashboard.countries.index'),
                    ),
                    array(
                        'title' => 'Travel Directions',
                        'url' => route('dashboard.directions.index'),
                    ),
                ),
            ),
            */
        );

        return self::setActiveMenus($menu);

    }

    public static function getSidebarMenu() {

        $menu = array(
            array(
                'title' => 'Store',
                'url' => '',
                'icon' => 'ki-basket',
                'childs' => array(
                    array(
                        'title' => 'Products',
                        'url' => route('dashboard.products.index'),
                    ),
                    array(
                        'title' => 'Orders',
                        'url' => route('dashboard.orders.index'),
                    ),
                    array(
                        'title' => 'Payment gateways',
                        'url' => route('dashboard.gateways.index'),
                    ),
                ),
            ),
            array(
                'title' => 'Content',
                'url' => '',
                'icon' => 'ki-file',
                'childs' => array(
                    array(
                        'title' => 'Articles',
                        'url' => route('dashboard.articles.index'),
                    ),
                    array(
                        'title' => 'Countries',
                        'url' => route('dashboard.countries.index'),
                    ),
                    array(
                        'title' => 'Travel Directions',
                        'url' => route('dashboard.directions.index'),
                    ),
                ),
            ),
            array(
                'title' => 'Settings',
                'url' => route('dashboard.settings.index'),
                'icon' => 'ki-element-11',
                'childs' => array(
                    
                    array(
                        'title' => 'General',
                        'url' => route('dashboard.settings.index'),
                    ),
                ),

            ),
        );

        return self::setActiveMenus($menu);

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