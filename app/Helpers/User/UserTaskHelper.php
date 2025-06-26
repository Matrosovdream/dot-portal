<?php

namespace App\Helpers\User;

class UserTaskHelper {

    public function updateUserTasks() {

        $driverRepo = app('App\Repositories\Driver\DriverRepo');
        
        $items = $driverRepo->getAll([], 10000)['items'];

        $invalidItems = [];
        foreach( $items as $key=>$item ) {

            $validationHelper = app('App\Helpers\Validation\Models\DriverValidation');
            $validation = $validationHelper->setData($item)->validateAll();

            if( $validation['valid'] ) {
                continue;
            }

            $invalidItems[$key] = $item;
            $invalidItems[$key]['Validation'] = $validation;

        }

        dd($invalidItems);

    }



}