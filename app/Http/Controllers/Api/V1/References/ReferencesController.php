<?php

namespace App\Http\Controllers\Api\V1\References;

use App\Actions\Api\V1\References\ReferencesActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\References\ReferenceResource;

class ReferencesController extends Controller
{
    public function __construct(protected ReferencesActions $actions) {}

    public function states()                  { return ReferenceResource::collection($this->actions->states()); }
    public function driverTypes()             { return ReferenceResource::collection($this->actions->driverTypes()); }
    public function licenseTypes()            { return ReferenceResource::collection($this->actions->licenseTypes()); }
    public function licenseEndorsements()     { return ReferenceResource::collection($this->actions->licenseEndorsements()); }
    public function vehicleOwnershipTypes()   { return ReferenceResource::collection($this->actions->vehicleOwnershipTypes()); }
    public function vehicleUnitTypes()        { return ReferenceResource::collection($this->actions->vehicleUnitTypes()); }
    public function queryPrices()             { return ReferenceResource::collection($this->actions->queryPrices()); }
    public function paymentMethods()          { return ReferenceResource::collection($this->actions->paymentMethods()); }
    public function requestStatuses()         { return ReferenceResource::collection($this->actions->requestStatuses()); }
    public function orderStatuses()           { return ReferenceResource::collection($this->actions->orderStatuses()); }
    public function serviceGroups()           { return ReferenceResource::collection($this->actions->serviceGroups()); }

    public function bundle()
    {
        $bundle = $this->actions->bundle();

        return response()->json([
            'data' => [
                'states'                  => ReferenceResource::collection($bundle['states'])->resolve(),
                'driver_types'            => ReferenceResource::collection($bundle['driver_types'])->resolve(),
                'license_types'           => ReferenceResource::collection($bundle['license_types'])->resolve(),
                'license_endorsements'    => ReferenceResource::collection($bundle['license_endorsements'])->resolve(),
                'vehicle_ownership_types' => ReferenceResource::collection($bundle['vehicle_ownership_types'])->resolve(),
                'vehicle_unit_types'      => ReferenceResource::collection($bundle['vehicle_unit_types'])->resolve(),
                'query_prices'            => ReferenceResource::collection($bundle['query_prices'])->resolve(),
                'payment_methods'         => ReferenceResource::collection($bundle['payment_methods'])->resolve(),
                'request_statuses'        => ReferenceResource::collection($bundle['request_statuses'])->resolve(),
                'order_statuses'          => ReferenceResource::collection($bundle['order_statuses'])->resolve(),
                'service_groups'          => ReferenceResource::collection($bundle['service_groups'])->resolve(),
            ],
        ]);
    }
}
