<?php

namespace App\References\Menu;

use App\Repositories\References\RefServiceGroupRepo;

class DriverMenu implements InterfaceMenu {

    public function getMenus() : array {

        return [
            'sidebar' => $this->sidebarMenu(),
        ];

    }

    public function sidebarMenu() : array {

        return array(
            array(
                'title' => 'Notifications',
                'url' => '',
                'icon' => 'ki-notification',
                'roles' => ['user'],
                'childs' => array(
                    array(
                        'title' => 'Active',
                        'url' => route('dashboard.notifications'),
                        'route' => 'dashboard.notifications',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),
            ),
            array(
                'title' => 'To-Do List',
                'url' => route('dashboard.todo.index'),
                'route' => 'dashboard.todo.index',
                'icon' => 'ki-check-square',
                'roles' => ['driver', 'company'],
                'childs' => array(
                    array(
                        'title' => 'All tasks',
                        'url' => route('dashboard.todo.index'),
                        'route' => 'dashboard.todo.index',
                        'roles' => ['driver', 'company'],
                    ),
                ),
            ),
            array(
                'title' => 'Documents',
                'url' => '',
                'icon' => 'ki-document',
                'roles' => ['user', 'manager', 'admin'],
                'childs' => array(
                    array(
                        'title' => 'All documents',
                        'url' => route('dashboard.documents.index'),
                        'route' => 'dashboard.documents.index',
                        'roles' => ['user', 'manager', 'admin'],
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
                        'title' => 'Settings',
                        'url' => route('dashboard.profile.show'),
                        'route' => 'dashboard.profile.show',
                        'roles' => ['user'],
                    ),
                ),
            ),
        );

    }


}