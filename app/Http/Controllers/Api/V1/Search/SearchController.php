<?php

namespace App\Http\Controllers\Api\V1\Search;

use App\Actions\Api\V1\Search\SearchActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Drivers\DriverListItemResource;
use App\Http\Resources\V1\ServiceRequests\ServiceRequestResource;
use App\Http\Resources\V1\Vehicles\VehicleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected SearchActions $actions) {}

    public function global(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $buckets = $this->actions->global($data['q'], $request->user());

        return response()->json([
            'data' => [
                'drivers' => [
                    'count' => $buckets['drivers']->count(),
                    'items' => DriverListItemResource::collection($buckets['drivers']),
                ],
                'vehicles' => [
                    'count' => $buckets['vehicles']->count(),
                    'items' => VehicleResource::collection($buckets['vehicles']),
                ],
                'service_requests' => [
                    'count' => $buckets['service_requests']->count(),
                    'items' => ServiceRequestResource::collection($buckets['service_requests']),
                ],
            ],
        ]);
    }
}
