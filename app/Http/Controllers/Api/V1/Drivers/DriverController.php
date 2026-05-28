<?php

namespace App\Http\Controllers\Api\V1\Drivers;

use App\Actions\Api\V1\Drivers\DriverActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Drivers\StoreDriverRequest;
use App\Http\Requests\Api\V1\Drivers\UpdateDriverRequest;
use App\Http\Resources\V1\Drivers\DriverListItemResource;
use App\Http\Resources\V1\Drivers\DriverResource;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct(protected DriverActions $actions) {}

    public function index(Request $request)
    {
        return DriverListItemResource::collection($this->actions->index($request, $request->user()));
    }

    public function store(StoreDriverRequest $request): JsonResponse
    {
        $driver = $this->actions->store($request->validated(), $request->user());
        return (new DriverResource($driver->load('user')))->response()->setStatusCode(201);
    }

    public function show(Request $request, Driver $driver)
    {
        return new DriverResource($this->actions->show($driver, $request->user()));
    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        return new DriverResource($this->actions->update($driver, $request->validated(), $request->user()));
    }

    public function destroy(Request $request, Driver $driver)
    {
        $this->actions->destroy($driver, $request->user());
        return response()->noContent();
    }

    public function terminate(Request $request, Driver $driver)
    {
        return new DriverResource($this->actions->terminate($driver, $request->user()));
    }

    public function sendOnetime(Request $request, Driver $driver): JsonResponse
    {
        $this->actions->sendOnetime($driver, $request->user());
        return response()->json(['message' => 'One-time login link sent.']);
    }
}
