<?php
namespace App\References;

use App\Repositories\References\RefServiceGroupRepo;

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
                    array(
                        'title' => 'Service groups',
                        'url' => route('dashboard.servicegroups.index'),
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
                'roles' => ['user', 'manager', 'admin'],
                'childs' => array(
                    array(
                        'title' => 'All',
                        'url' => route('dashboard.drivers.index'),
                        'roles' => ['user', 'manager', 'admin'],
                    ),
                    array(
                        'title' => 'New driver',
                        'url' => route('dashboard.drivers.create'),
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
                        'roles' => ['user'],
                    ),
                    array(
                        'title' => 'Subscription',
                        'url' => route('dashboard.subscription.index'),
                        'roles' => ['user'],
                    ),
                ),
            ),
            array(
                'title' => 'Service request',
                'url' => '',
                'icon' => 'ki-request',
                'roles' => ['user'],
                'childs' => (new self)->getUserRequestGroups(),
            ),
        );

    }

    public function getUserRequestGroups( $user_id = null ) {

        $urls = [];

        $groups = (new RefServiceGroupRepo)->getAll([], $paginate = 1000);
        foreach( $groups['items'] as $group ) {
            $urls[] = array(
                'title' => $group['name'],
                'url' => route('dashboard.servicerequest.group', ['group' => $group['slug']]),
                'roles' => ['user'],
            );
        }

        return $urls;

    }

}