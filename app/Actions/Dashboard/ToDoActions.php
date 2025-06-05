<?php
namespace App\Actions\Dashboard;

use App\Mixins\Integrations\SaferwebAPI;
use App\Models\Service;
use App\Repositories\User\UserTaskRepo;


class ToDoActions {

    private $todoRepo;

    public function __construct()
    {
        $this->todoRepo = new UserTaskRepo();

    }

    public function index()
    {

        $safer = (new SaferwebAPI())->getCompanySnapshot(2553306);
        dd($safer);

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