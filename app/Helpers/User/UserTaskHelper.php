<?php

namespace App\Helpers\User;

class UserTaskHelper {

    public function updateDriverTasks() {
        
        $invalidItems = $this->validateModelRecords(
            app('App\Repositories\Driver\DriverRepo'),
            app('App\Helpers\Validation\Models\DriverValidation'),
            10000
        );

        dd($invalidItems);

    }

    public function updateVehicleTasks() {

        $invalidItems = $this->validateModelRecords(
            app('App\Repositories\Vehicle\VehicleRepo'),
            app('App\Helpers\Validation\Models\VehicleValidation'),
            10000
        );

        dd($invalidItems);

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



}