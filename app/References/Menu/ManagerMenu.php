<?php

namespace App\References\Menu;

class ManagerMenu implements InterfaceMenu {

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
                'icon' => 'ki-basket',
                'roles' => ['admin'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => route('dashboard.notifications-manage.index'),
                        'route' => 'dashboard.notifications-manage.index',
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
                        'route' => 'dashboard.services.index',
                        'roles' => ['admin', 'manager'],
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
        );

    }

}