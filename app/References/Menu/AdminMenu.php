<?php

namespace App\References\Menu;

class AdminMenu implements InterfaceMenu {

    public function getMenus() : array {

        return [
            'sidebar' => $this->sidebarMenu(),
        ];

    }

    public function sidebarMenu() : array {

        return array(
            array(
                'title' => 'Notifications manager',
                'url' => '',
                'icon' => 'ki-notification',
                'roles' => ['admin'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => route('dashboard.notifications-manage.index'),
                        'route' => 'dashboard.notifications-manage.index',
                        'roles' => ['admin', 'manager'],
                    ),
                    array(
                        'title' => 'New notification',
                        'url' => route('dashboard.notifications-manage.create'),
                        'route' => 'dashboard.notifications-manage.create',
                        'roles' => ['admin', 'manager'],
                    ),
                ),
            ),
            array(
                'title' => 'Services',
                'url' => '',
                'icon' => 'ki-setting',
                'roles' => ['admin', 'manager'],
                'childs' => array(
                    array(
                        'title' => 'Services list',
                        'url' => route('dashboard.services.index'),
                        'route' => 'dashboard.services.index',
                        'roles' => ['admin', 'manager'],
                    ),
                    array(
                        'title' => 'Service fields',
                        'url' => route('dashboard.servicefields.index'),
                        'route' => 'dashboard.servicefields.index',
                        'roles' => ['admin'],
                    ),
                    array(
                        'title' => 'Service groups',
                        'url' => route('dashboard.servicegroups.index'),
                        'route' => 'dashboard.servicegroups.index',
                        'roles' => ['admin'],
                    ),

                ),
            ),
            array(
                'title' => 'Requests',
                'url' => '',
                'icon' => 'ki-message-question',
                'roles' => ['admin', 'manager'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => route('dashboard.requestmanage.index'),
                        'route' => 'dashboard.requestmanage.index',
                        'roles' => ['admin', 'manager'],
                    ),
                ),
            ),
            // Subscriptions
            array(
                'title' => 'Subscriptions',
                'url' => '',
                'icon' => 'ki-message-question',
                'roles' => ['admin', 'manager'],
                'childs' => array(
                    array(
                        'title' => 'Initial fees',
                        'url' => route('dashboard.planfees.index'),
                        'route' => 'dashboard.planfees.index',
                        'roles' => ['admin', 'manager'],
                    ),
                    array(
                        'title' => 'Plans',
                        'url' => route('dashboard.subplans.index'),
                        'route' => 'dashboard.subplans.index',
                        'roles' => ['admin', 'manager'],
                    ),
                    // Custom subscription requests
                    array(
                        'title' => 'Custom requests',
                        'url' => route('dashboard.subrequests.index'),
                        'route' => 'dashboard.subrequests.index',
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
                        'route' => 'dashboard.settings.index',
                        'roles' => ['admin'],
                    ),
                    array(
                        'title' => 'Users',
                        'url' => route('dashboard.users.index'),
                        'route' => 'dashboard.users.index',
                        'roles' => ['admin'],
                    ),
                ),

            ),
        );

    }

}