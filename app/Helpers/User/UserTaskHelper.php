<?php

namespace App\Helpers\User;

class UserTaskHelper {

    public function updateDriverTasks() {
        
        $invalidItems = $this->validateModelRecords(
            app('App\Repositories\Driver\DriverRepo'),
            app('App\Helpers\Validation\Models\DriverValidation'),
            10000
        );

        $tasks = [];
        foreach( $invalidItems as $key=>$item ) {

            $tabsRef = $item['Validation']['tabs'];
            $item['user']['id'] = 3;

            foreach( $item['Validation']['errors'] as $tabCode=>$tabs ) {

                $tabTitle = $tabsRef[$tabCode]['title'] ?? '';

                $uniques = [
                    'validation', // Task type
                    'driver', // Model
                    $item['id'], // Element ID
                    strtolower($tabCode) // Tab code
                ];
                $uniqueCode = $this->prepareUniqueCode($uniques);

                $tasks[$uniqueCode] = [
                    'unique_code' => $uniqueCode,
                    'user_id' => $item['user']['id'] ?? null,
                    'assigned_to' => $item['user']['id'] ?? null,
                    'title' => 'Driver #'.$item['id'].' Validation Error: '.$tabTitle,
                    'description' => 'Driver #'.$item['id'].' has validation errors in tab: '.$tabTitle,
                    'category' => 'driver',
                    'subcategory' => $tabTitle,
                    'status' => 'open',
                    'due_date' => now()->addDays(7),
                    'priority' => 'normal',
                    'link' => route('dashboard.drivers.show', $item['id']),
                    'entity' => 'driver',
                    'entity_id' => $item['id'],
                ];

            }

        }

        // Add into DB
        $this->syncTasks($tasks);

        return $tasks;

    }

    public function updateVehicleTasks() {

        $invalidItems = $this->validateModelRecords(
            app('App\Repositories\Vehicle\VehicleRepo'),
            app('App\Helpers\Validation\Models\VehicleValidation'),
            10000
        );

        $tasks = [];
        foreach( $invalidItems as $key=>$item ) {

            $tabsRef = $item['Validation']['tabs'];
            $item['driver']['user_id'] = 3;

            foreach( $item['Validation']['errors'] as $tabCode=>$tabs ) {

                $tabTitle = $tabsRef[$tabCode]['title'] ?? '';

                $uniques = [
                    'validation', // Task type
                    'vehicle', // Model
                    $item['id'], // Element ID
                    strtolower($tabCode) // Tab code
                ];
                $uniqueCode = $this->prepareUniqueCode($uniques);

                $tasks[$uniqueCode] = [
                    'unique_code' => $uniqueCode,
                    'user_id' => $item['driver']['user_id'] ?? null,
                    'assigned_to' => $item['driver']['user_id'] ?? null,
                    'title' => 'Vehicle #'.$item['id'].' Validation Error: '.$tabTitle,
                    'description' => 'Vehicle #'.$item['id'].' has validation errors in tab: '.$tabTitle,
                    'category' => 'vehicle',
                    'subcategory' => $tabTitle,
                    'status' => 'open',
                    'due_date' => now()->addDays(7),
                    'priority' => 'normal',
                    'link' => route('dashboard.vehicles.show', $item['id']),
                    'entity' => 'vehicle',
                    'entity_id' => $item['id'],
                ];

            }

        }

        // Add into DB
        $this->syncTasks($tasks);

        return $tasks;

    }

    public function updateExpireTasks() {

        $expHelper = app('App\Helpers\Expiration\ExpirationHelper');

        // Get all expired items
        $models = $expHelper->getExpiredItems();

        $tasks = [];
        foreach( $models as $modelData ) {

            // Loop through each model and its items
            $modelTitle = ucfirst( $modelData['entity'] );
            $items = $modelData['items'];
            $tabCode = $modelData['field'];

            foreach( $items as $item ) {

                $item['user_id'] = 3;
            
                $uniques = [
                    'expiration', // Task type
                    $modelData['entity'], // Model
                    $item['id'], // Element ID
                    strtolower($tabCode) // Tab code
                ];
                $uniqueCode = $this->prepareUniqueCode($uniques);

                $link = '';
                switch( $modelData['entity'] ) {
                    case 'driver':
                        $link = route('dashboard.drivers.show', $item['id']);
                        break;
                    case 'vehicle':
                        $link = route('dashboard.vehicles.show', $item['id']);
                        break;
                    // Add more cases as needed for other entities
                }

                $tasks[$uniqueCode] = [
                    'unique_code' => $uniqueCode,
                    'user_id' => 3,
                    'assigned_to' => 3,
                    'title' => $modelTitle.' #'.$item['id'].' Expiration Alert: '.$modelData['title'],
                    //'description' => 'Vehicle #'.$item['id'].' has validation errors in tab: '.$tabTitle,
                    'category' => $modelData['entity'],
                    'subcategory' => $modelData['title'],
                    'status' => 'open',
                    'due_date' => now()->addDays(7),
                    'priority' => 'normal',
                    'link' => $link,
                    'entity' => $modelData['entity'],
                    'entity_id' => $item['id'],
                ];

            }
        }

        // Add into DB
        $this->syncTasks($tasks);

        return $tasks;

    }

    public function validateModelRecords( $modelRepo, $validationHelper, $limit = 10000 ) {

        $items = $modelRepo->getAll([], $limit)['items'];

        $invalidItems = [];
        foreach( $items as $key=>$item ) {

            $validation = $validationHelper->setData($item)->validateAll();

            if( $validation['valid'] ) {
                continue;
            }

            $invalidItems[$key] = $item;
            $invalidItems[$key]['Validation'] = $validation;

        }

        return $invalidItems;

    }

    private function syncTasks( $tasks ) {

        if( empty($tasks) ) {
            return;
        }

        $taskRepo = app('App\Repositories\User\UserTaskRepo');

        // Mass update by unique code Upsert
        $taskRepo->model->upsert(
            $tasks,
            ['unique_code'],
        );

    }

    private function prepareUniqueCode( $data ) {

        return implode('_', $data);

    }

}