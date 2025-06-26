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
                    'link' => 'dashboard/drivers/'.$item['id'],
                    'entity' => 'driver',
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