<?php

namespace App\Http\Controllers\Api\V1\Vehicles;

use App\Actions\Api\V1\Vehicles\VehicleActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vehicles\StoreVehicleRequest;
use App\Http\Requests\Api\V1\Vehicles\UpdateVehicleRequest;
use App\Http\Resources\V1\Vehicles\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct(protected VehicleActions $actions) {}

    public function index(Request $request)
    {
        return VehicleResource::collection($this->actions->index($request, $request->user()));
    }

    public function store(StoreVehicleRequest $request)
    {
        $vehicle = $this->actions->store($request->validated(), $request->user());
        return (new VehicleResource($vehicle))->response()->setStatusCode(201);
    }

    public function show(Request $request, Vehicle $vehicle)
    {
        return new VehicleResource($this->actions->show($vehicle, $request->user()));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        return new VehicleResource($this->actions->update($vehicle, $request->validated(), $request->user()));
    }

    public function destroy(Request $request, Vehicle $vehicle)
    {
        $this->actions->destroy($vehicle, $request->user());
        return response()->noContent();
    }
}
