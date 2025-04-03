<?php

namespace App\References\Menu;

use App\Repositories\References\RefServiceGroupRepo;

class CompanyMenu implements InterfaceMenu {

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
                'title' => 'Documents',
                'url' => '',
                'icon' => 'ki-notification',
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
                'title' => 'Drivers',
                'url' => '',
                'icon' => 'ki-notification',
                'roles' => ['user', 'manager', 'admin'],
                'childs' => array(
                    array(
                        'title' => 'All drivers',
                        'url' => route('dashboard.drivers.index'),
                        'route' => 'dashboard.drivers.index',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                    array(
                        'title' => 'New driver',
                        'url' => route('dashboard.drivers.create'),
                        'route' => 'dashboard.drivers.create',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),
            ),
            array(
                'title' => 'Vehicles',
                'url' => '',
                'icon' => 'ki-notification',
                'roles' => ['user', 'manager', 'admin'],
                'childs' => array(
                    array(
                        'title' => 'All vehicles',
                        'url' => route('dashboard.vehicles.index'),
                        'route' => 'dashboard.vehicles.index',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                    array(
                        'title' => 'New vehicle',
                        'url' => route('dashboard.vehicles.create'),
                        'route' => 'dashboard.vehicles.create',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),
            ),
            array(
                'title' => 'Service request',
                'url' => '',
                'icon' => 'ki-request',
                'roles' => ['user'],
                'childs' => $this->getUserRequestGroups(),
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
                    array(
                        'title' => 'Subscription',
                        'url' => route('dashboard.subscription.index'),
                        'route' => 'dashboard.subscription.index',
                        'roles' => ['user'],
                    ),
                ),
            ),
        );

    }

    private function getUserRequestGroups( $user_id = null ) {

        $urls = [];

        $urls[] = array(
            'title' => 'My requests',
            'url' => route('dashboard.servicerequest.history.index'),
            'route' => 'dashboard.servicerequest.history.index',
            'roles' => ['user'],
        );

        $groups = (new RefServiceGroupRepo)->getAll([], $paginate = 1000);
        foreach( $groups['items'] as $group ) {
            $urls[] = array(
                'title' => $group['name'],
                'url' => route('dashboard.servicerequest.group', ['group' => $group['slug']]),
                'route' => 'dashboard.servicerequest.group',
                'roles' => ['user'],
            );
        }
        
        return $urls;

    }


}