<?php

namespace App\Helpers;

use App\Repositories\User\UserTaskRepo;
use App\Helpers\Expiration\ExpirationHelper;



class UserTaskHelper {

    public function addExpireDocumentTasks() {

        $expHelper = app(ExpirationHelper::class);
        $taskRepo = app(UserTaskRepo::class);

        // Get all expired items
        $models = $expHelper->getExpiredItems();

        $tasks = [];
        foreach( $models as $modelData ) {

            // Loop through each model and its items
            $modelTitle = $modelData['title'];
            $items = $modelData['items'];

            foreach( $items as $item ) {
            
                $tasks[] = [
                    'user_id' => $item['user_id'], // Assuming each item has a user_id
                    'assigned_to' => $item['user_id'], // Assuming each item has a user_id
                    'title' => "Expire {$modelTitle} for {$item['id']}",
                    'description' => "{$modelTitle} for {$item['id']} is expired or will expire soon.",
                    'status' => 'pending',
                    'category' => $item['entity'],
                    'subcategory' => '',
                    'model_id' => $item['id'],
                ];

            }
        }

        // Add tasks to the repository
        foreach( $tasks as $task ) {
            $taskRepo->create($task);       
        }

    }


}
