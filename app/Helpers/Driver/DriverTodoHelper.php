<?php 

namespace App\Helpers\Driver;

use App\Repositories\Driver\DriverRepo;

class DriverTodoHelper {

    public function addTodoTasks(array $filter = []): void
    {
        $driverRepo = app(DriverRepo::class);
        $driverValidation = app('App\Helpers\Validation\Models\DriverValidation');

        // Get all drivers based on the filter
        $drivers = $driverRepo->getAll($filter, $paginate = 100000);

        // Validate drivers
        $validation = [];
        foreach( $drivers['items'] as $key => $driver ) {
            $dataSet = array_merge(
                $driverValidation->setData($driver)->validateAll(),
                array('driver' => $driver)
            );
            $validation[ $driver['id'] ]= $dataSet;
        }

        foreach( $validation as $key => $data ) {
            if( $data['valid'] ) {
                continue;
            }

            // Process the valid driver data
            $this->addTodoTask($data);
        }

        

    }

    public function addTodoTask( $data ) {

        $taskRepo = app('App\Repositories\User\UserTaskRepo');

        // Loop through errors
        $errorSets = [];
        foreach( $data['errors'] as $subcategory=>$errors ) {

            $driverTitle = $data['driver']['firstname'] . ' ' . $data['driver']['lastname'];

            $errorTitles = [];
            foreach( $errors as $error ) {
                $errorTitles[] = $error['title'];
            }

            $taskSet = [
                'user_id' => $data['driver']['user_id'],
                'assigned_to' => $data['driver']['user_id'],
                'title' => 'Driver Validation Error for '.$driverTitle,
                'description' => implode(', ', $errorTitles),
                'category' => 'driver',
                'subcategory' => $subcategory,
                'status' => 'pending',
                'priority' => 'normal',
                'link' => route('dashboard.drivers.show', $data['driver']['id']),
                'entity' => 'driver',
                'entity_id' => $data['driver']['id'],
            ];
            $errorSets[] = $taskSet;
            
        }

        foreach( $errorSets as $taskSet ) {
            
            // If the task already exists, skip it
            $exists = $taskRepo->getAll([
                'user_id' => $taskSet['user_id'],
                'subcategory' => $taskSet['subcategory'],
                'entity' => $taskSet['entity'],
                'entity_id' => $taskSet['entity_id'],
            ]);
            echo $exists['items']->count() . ' tasks found for ' . $taskSet['title'] . '<br/>';

            if( $exists['items']->isEmpty() ) {
                // Add a task for each error
                $taskRepo->create($taskSet);
            }

        }

        dd($data, $errorSets);

    } 

}