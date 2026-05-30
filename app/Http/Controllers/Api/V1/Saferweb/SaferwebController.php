<?php

namespace App\Http\Controllers\Api\V1\Saferweb;

use App\Actions\Api\V1\Saferweb\SaferwebActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Saferweb\VehicleCrashSaferwebListItemResource;
use App\Http\Resources\V1\Saferweb\VehicleCrashSaferwebResource;
use App\Http\Resources\V1\Saferweb\VehicleInspectionSaferwebListItemResource;
use App\Http\Resources\V1\Saferweb\VehicleInspectionSaferwebResource;
use App\Models\VehicleCrashSaferweb;
use App\Models\VehicleInspectionSaferweb;
use Illuminate\Http\Request;

class SaferwebController extends Controller
{
    public function __construct(private SaferwebActions $actions)
    {
    }

    public function inspectionsIndex(Request $request)
    {
        $inspections = $this->actions->indexInspections($request, $request->user());

        return VehicleInspectionSaferwebListItemResource::collection($inspections);
    }

    public function inspectionShow(Request $request, VehicleInspectionSaferweb $inspection)
    {
        $this->actions->showInspection($inspection, $request->user());

        return new VehicleInspectionSaferwebResource($inspection);
    }

    public function crashesIndex(Request $request)
    {
        $crashes = $this->actions->indexCrashes($request, $request->user());

        return VehicleCrashSaferwebListItemResource::collection($crashes);
    }

    public function crashShow(Request $request, VehicleCrashSaferweb $crash)
    {
        $this->actions->showCrash($crash, $request->user());

        return new VehicleCrashSaferwebResource($crash);
    }
}
