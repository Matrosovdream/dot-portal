<?php

namespace App\Http\Controllers\Api\V1\InsuranceVehicles;

use App\Actions\Api\V1\InsuranceVehicles\InsuranceVehicleActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InsuranceVehicles\StoreInsuranceVehicleRequest;
use App\Http\Requests\Api\V1\InsuranceVehicles\UpdateInsuranceVehicleRequest;
use App\Http\Resources\V1\InsuranceVehicles\InsuranceVehicleResource;
use App\Models\InsuranceVehicle;
use Illuminate\Http\Request;

class InsuranceVehicleController extends Controller
{
    public function __construct(protected InsuranceVehicleActions $actions) {}

    public function index(Request $request)
    {
        return InsuranceVehicleResource::collection($this->actions->index($request, $request->user()));
    }

    public function store(StoreInsuranceVehicleRequest $request)
    {
        $rec = $this->actions->store($request->validated(), $request->user());
        return (new InsuranceVehicleResource($rec))->response()->setStatusCode(201);
    }

    public function show(Request $request, InsuranceVehicle $insurance)
    {
        return new InsuranceVehicleResource($this->actions->show($insurance, $request->user()));
    }

    public function update(UpdateInsuranceVehicleRequest $request, InsuranceVehicle $insurance)
    {
        return new InsuranceVehicleResource($this->actions->update($insurance, $request->validated(), $request->user()));
    }

    public function destroy(Request $request, InsuranceVehicle $insurance)
    {
        $this->actions->destroy($insurance, $request->user());
        return response()->noContent();
    }
}
