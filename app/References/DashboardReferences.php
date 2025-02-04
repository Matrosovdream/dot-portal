<?php
namespace App\References;

class DashboardReferences {

    public static function sidebarMenu() {

        return array(
            array(
                'title' => 'Notifications manager',
                'url' => '',
                'icon' => 'ki-basket',
                'roles' => ['admin'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => route('dashboard.notifications-manage.index'),
                        'roles' => ['admin', 'manager'],
                    ),
                ),
            ),


            array(
                'title' => 'Services',
                'url' => '',
                'icon' => 'ki-basket',
                'roles' => ['admin', 'manager'],
                'childs' => array(
                    array(
                        'title' => 'Services list',
                        'url' => route('dashboard.services.index'),
                        'roles' => ['admin', 'manager'],
                    ),
                    array(
                        'title' => 'Service fields',
                        'url' => route('dashboard.servicefields.index'),
                        'roles' => ['admin'],
                    ),

                ),
            ),
            array(
                'title' => 'Requests',
                'url' => '',
                'icon' => 'ki-element-9',
                'roles' => ['admin', 'manager'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => route('dashboard.admin.requests.index'),
                        'roles' => ['admin', 'manager'],
                    ),
                ),
            ),
            array(
                'title' => 'Settings',
                'url' => route('dashboard.settings.index'),
                'icon' => 'ki-element-11',
                'roles' => ['admin'],
                'childs' => array(
                    array(
                        'title' => 'General',
                        'url' => route('dashboard.settings.index'),
                        'roles' => ['admin'],
                    ),
                    array(
                        'title' => 'Users',
                        'url' => route('dashboard.users.index'),
                        'roles' => ['admin'],
                    ),
                ),

            ),
            array(
                'title' => 'Notifications',
                'url' => '',
                'icon' => 'ki-notification',
                'roles' => ['user'],
                'childs' => array(
                    array(
                        'title' => 'Active',
                        'url' => route('dashboard.notifications'),
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),
            ),
            array(
                'title' => 'Drivers',
                'url' => '',
                'icon' => 'ki-notification',
                'roles' => ['user'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => route('dashboard.drivers.index'),
                        'roles' => ['user'],
                    ),
                    array(
                        'title' => 'New driver',
                        'url' => route('dashboard.drivers.create'),
                        'roles' => ['user'],
                    ),
                ),
            ),
            /*array(
                'title' => 'Service request',
                'url' => '',
                'icon' => 'ki-request',
                'roles' => ['user'],
                'childs' => array(
                    array(
                        'title' => 'Some service',
                        'url' => '/',
                        'roles' => ['user'],
                    ),
                ),
            ),
            array(
                'title' => 'Drivers',
                'url' => '',
                'icon' => 'ki-request',
                'roles' => ['user'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => '/',
                        'roles' => ['user'],
                    ),
                ),
            ),
            array(
                'title' => 'My cabinet',
                'url' => '',
                'icon' => 'ki-user',
                'roles' => ['user'],
                'childs' => array(
                    array(
                        'title' => 'Profile',
                        'url' => route('dashboard.profile'),
                        'roles' => ['user'],
                    ),
                    array(
                        'title' => 'Orders',
                        'url' => route('dashboard.my-orders'),
                        'roles' => ['user'],
                    ),
                ),
            ),
            */
        );

    }

}