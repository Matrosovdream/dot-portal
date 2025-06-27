<?php
namespace App\Actions\Dashboard;

use App\Mixins\Integrations\SaferwebAPI;
use App\Models\Service;
use App\Repositories\User\UserTaskRepo;
use App\Helpers\User\CompanyHelper;
use App\Helpers\User\UserTaskHelper;



class ToDoActions {

    private $todoRepo;

    public function __construct()
    {
        $this->todoRepo = new UserTaskRepo();

    }

    public function index()
    {

        $taskHelper = new CompanyHelper();
        $safer = new SaferwebAPI();
        $userTaskHelper = app('App\Helpers\User\UserTaskHelper');

        if( request()->has('update_snapshot')) {
            $safer = $taskHelper->updateSnapshot(1);
            dd($safer);
        } 

        if( request()->has('update_crashes')) {
            $safer = $taskHelper->updateCrashes(1);
            dd($safer);
        } 

        if( request()->has('update_inspections')) {
            $safer = $taskHelper->updateInspections(1);
            dd($safer);
        } 

        if( request()->has('number') ) {
            $dotNumber = request()->input('number');
        } else {
            $dotNumber = 4296324;
        }

        if( request()->has('dot') ) {
            $safer = $safer->getCompanySnapshot($dotNumber);
            dd($safer);
        }
        if( request()->has('inspection') ) {
            $safer = $safer->getInspectionHistory($dotNumber)['inspection_records'][2];
            dd($safer);
        }
        if( request()->has('crash') ) {
            $safer = $safer->getCrashHistory($dotNumber)['crash_records'];
            dd($safer);
        }
        if( request()->has('summary') ) {
            $safer = $safer->getInspectionSummary($dotNumber);
            dd($safer);
        }
        if( request()->has('history') ) {
            $safer = $safer->getHistoryAll($dotNumber)['violation_records'];
            dd($safer);
        }
        if( request()->has('tasks') ) {
            $itemsDriver = $userTaskHelper->updateDriverTasks();
            $itemsVehicle = $userTaskHelper->updateVehicleTasks();
            $itemsExpired = $userTaskHelper->updateExpireTasks();
            dd(
                vars: $itemsExpired
            );
        }

        $data = [
            'title' => 'To-Do List',
            'tasks' => $this->todoRepo->getAll( 
                ['user_id' => auth()->user()->id],
                $paginate = 10
            ),
        ];

        return $data;
    }

    public function company()
    {
        $data = [
            'title' => 'Company To-Do List',
            'tasks' => $this->todoRepo->getAll( 
                ['user_id' => auth()->user()->company_id, 'entity' => 'company'],
                $paginate = 20
            ),
        ];

        return $data;
    }

    public function driver()
    {
        $data = [
            'title' => 'Driver To-Do List',
            'tasks' => $this->todoRepo->getAll( 
                ['user_id' => auth()->user()->id, 'entity' => 'driver'],
                $paginate = 20
            ),
        ];

        return $data;
    }

    public function show( $task_id )
    {
        $task = $this->todoRepo->getByID( $task_id );

        if( !$task ) {
            abort(404, 'Task not found');
        }

        return [
            'title' => 'Task Details #' . $task['id'],
            'task' => $task,
        ];
    }

}