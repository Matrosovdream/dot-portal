<?php
namespace App\Actions\Dashboard;

use App\Mixins\Integrations\SaferwebAPI;
use App\Models\Service;
use App\Repositories\User\UserTaskRepo;
use App\Helpers\User\CompanyHelper;



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

        if( request()->has('update_snapshop')) {
            $safer = $taskHelper->updateSnapshot(1);
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
            $safer = $safer->getCrashHistory($dotNumber)['crash_records'][0];
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
       

        $data = [
            'title' => 'To-Do List',
            'tasks' => $this->todoRepo->getAll( 
                ['assigned_to' => auth()->user()->id],
                $paginate = 10
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