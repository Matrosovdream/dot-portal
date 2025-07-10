<?php
namespace App\Actions\Dashboard;

use App\Helpers\TranspGov\TranspGovSnapshot;
use App\Helpers\TranspGov\TranspGovInspection;
use App\Helpers\TranspGov\TranspGovCrash;
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

        if( request()->has('snapshots') ) {

            $transportGov = new TranspGovSnapshot();
            $transportGov->mapWithModel = true; // Enable mapping to model

            $items = $transportGov->getItemsByDot(
                [363, 44, 64, 111, 113],
                1000,
                'dot_number'
            );
            dd($items);
        }

        if( request()->has('inspections') ) {

            $transportGov = new TranspGovInspection();
            $transportGov->mapWithModel = true; // Enable mapping to model

            $items = $transportGov->getItemsByDot(
                [363, 44, 64, 111, 113],
                1000,
                'dot_number'
            );
            dd($items);
        }

        if( request()->has('crashes') ) {

            $transportGov = new TranspGovCrash();
            $transportGov->mapWithModel = true; // Enable mapping to model
            
            $items = $transportGov->getItemsByDot(
                [363, 44, 64, 111, 113],
                1000,
                'dot_number'
            );
            dd($items);
        }

        $data = [
            'title' => 'To-Do List',
            'tasks' => $this->todoRepo->getAll( 
                ['user_id' => auth()->user()->id],
                $paginate = 10
            ),
            'topMenu' => $this->getTopMenu(),
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
            'topMenu' => $this->getTopMenu(),
        ];

        return $data;
    }

    public function vehicle()
    {
        $data = [
            'title' => 'Vehicle To-Do List',
            'tasks' => $this->todoRepo->getAll( 
                ['user_id' => auth()->user()->id, 'entity' => 'vehicle'],
                $paginate = 20
            ),
            'topMenu' => $this->getTopMenu(),
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
            'topMenu' => $this->getTopMenu(),
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

    protected function getTopMenu() {

        return [
            array(
                'title' => 'All tasks',
                'url' => route('dashboard.todo.index'),
                'route' => 'dashboard.todo.index',
            ),
            array(
                'title' => 'Company tasks',
                'url' => route('dashboard.todo.company'),
                'route' => 'dashboard.todo.company',
            ),
            array(
                'title' => 'Vehicle tasks',
                'url' => route('dashboard.todo.vehicle'),
                'route' => 'dashboard.todo.vehicle',
            ),
            array(
                'title' => 'Driver tasks',
                'url' => route('dashboard.todo.driver'),
                'route' => 'dashboard.todo.driver',
            ),
        ];

    }

}