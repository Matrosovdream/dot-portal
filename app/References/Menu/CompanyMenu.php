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
                /*'childs' => array(
                    array(
                        'title' => 'Active',
                        'url' => route('dashboard.notifications'),
                        'route' => 'dashboard.notifications',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),
                */
            ),
            array(
                'title' => 'To-Do List',
                'url' => route('dashboard.todo.index'),
                'route' => 'dashboard.todo.index',
                'icon' => 'ki-check-square',
                'roles' => ['driver', 'company'],
                /*'childs' => array(
                    array(
                        'title' => 'All tasks',
                        'url' => route('dashboard.todo.index'),
                        'route' => 'dashboard.todo.index',
                        'roles' => ['driver', 'company'],
                    ),
                    array(
                        'title' => 'Company tasks',
                        'url' => route('dashboard.todo.company'),
                        'route' => 'dashboard.todo.company',
                        'roles' => ['driver', 'company'],
                    ),
                    array(
                        'title' => 'Vehicle tasks',
                        'url' => route('dashboard.todo.vehicle'),
                        'route' => 'dashboard.todo.vehicle',
                        'roles' => ['driver', 'company'],
                    ),
                    array(
                        'title' => 'Driver tasks',
                        'url' => route('dashboard.todo.driver'),
                        'route' => 'dashboard.todo.driver',
                        'roles' => ['driver', 'company'],
                    ),
                ),
                */
            ),
            array(
                'title' => 'Documents',
                'url' => '',
                'icon' => 'ki-document',
                'roles' => ['user', 'manager', 'admin'],
                /*'childs' => array(
                    array(
                        'title' => 'All documents',
                        'url' => route('dashboard.documents.index'),
                        'route' => 'dashboard.documents.index',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),*/
            ),
            array(
                'title' => 'Drivers',
                'url' => '',
                'icon' => 'ki-user',
                'roles' => ['user', 'manager', 'admin'],
                /*'childs' => array(
                    array(
                        'title' => 'All drivers',
                        'url' => route('dashboard.drivers.index'),
                        'route' => 'dashboard.drivers.index',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                    array(
                        'title' => 'Terminated drivers',
                        'url' => route('dashboard.drivers.terminated'),
                        'route' => 'dashboard.drivers.terminated',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                    array(
                        'title' => 'New driver',
                        'url' => route('dashboard.drivers.create'),
                        'route' => 'dashboard.drivers.create',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),*/
            ),
            array(
                'title' => 'Vehicles',
                'url' => '',
                'icon' => 'ki-car',
                'roles' => ['user', 'manager', 'admin'],
                /*'childs' => array(
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
                ),*/
            ),
            array(
                'title' => 'Insurance vehicles',
                'url' => '',
                'icon' => 'ki-shield',
                'roles' => ['user', 'manager', 'admin'],
                'childs' => array(
                    array(
                        'title' => 'All insurances',
                        'url' => route('dashboard.insurance-vehicles.index'),
                        'route' => 'dashboard.insurance-vehicles.index',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                    array(
                        'title' => 'New insurance',
                        'url' => route('dashboard.insurance-vehicles.create'),
                        'route' => 'dashboard.insurance-vehicles.create',
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                ),
            ),
            array(
                'title' => 'Service request',
                'url' => '',
                'icon' => 'ki-message-question',
                'roles' => ['user'],
                'childs' => $this->getUserRequestGroups(),
            ),
            // Saferweb menu
            array(
                'title' => 'Saferweb',
                'url' => '',
                'icon' => 'ki-chart',
                'roles' => ['user', 'company'],
                'childs' => array(
                    array(
                        'title' => 'Inspections',
                        'url' => route('dashboard.saferweb.inspections.index'),
                        'route' => 'dashboard.saferweb.inspections.index',
                        'roles' => ['user', 'company'],
                    ),
                    array(
                        'title' => 'Crashes',
                        'url' => route('dashboard.saferweb.crashes.index'),
                        'route' => 'dashboard.saferweb.crashes.index',
                        'roles' => ['user', 'company'],
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