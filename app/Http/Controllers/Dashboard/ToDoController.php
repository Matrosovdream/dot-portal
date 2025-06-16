<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\ToDoActions;

class ToDoController extends Controller
{

    private $todoActions;

    public function __construct()
    {
        $this->todoActions = new ToDoActions();
    }

    public function index( Request $request )
    {

        return view(
            'dashboard.todo.index', 
            $this->todoActions->index()
        );

    }

    public function company( Request $request )
    {
        return view(
            'dashboard.todo.index', 
            $this->todoActions->company()
        );
    }

    public function driver( Request $request )
    {
        return view(
            'dashboard.todo.index', 
            $this->todoActions->driver()
        );
    }

    public function show( $task_id )
    {
        return view(
            'dashboard.todo.show', 
            $this->todoActions->show($task_id)
        );
    }

}
