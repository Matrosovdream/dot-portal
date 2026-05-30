<?php

namespace App\Http\Controllers\Api\V1\Todo;

use App\Actions\Api\V1\Todo\TodoActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Todo\TaskResource;
use App\Models\UserTask;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct(protected TodoActions $actions) {}

    public function index(Request $request)
    {
        return TaskResource::collection($this->actions->index($request, $request->user()));
    }

    public function company(Request $request)
    {
        return TaskResource::collection($this->actions->listByEntity($request, $request->user(), 'company'));
    }

    public function vehicle(Request $request)
    {
        return TaskResource::collection($this->actions->listByEntity($request, $request->user(), 'vehicle'));
    }

    public function driver(Request $request)
    {
        return TaskResource::collection($this->actions->listByEntity($request, $request->user(), 'driver'));
    }

    public function show(Request $request, UserTask $task)
    {
        return new TaskResource($this->actions->show($task, $request->user()));
    }
}
