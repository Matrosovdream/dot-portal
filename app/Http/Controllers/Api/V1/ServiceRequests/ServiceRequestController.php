<?php

namespace App\Http\Controllers\Api\V1\ServiceRequests;

use App\Actions\Api\V1\ServiceRequests\ServiceRequestActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ServiceRequests\ServiceRequestResource;
use App\Models\Request as ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function __construct(protected ServiceRequestActions $actions) {}

    public function index(Request $request)
    {
        return ServiceRequestResource::collection($this->actions->index($request, $request->user()));
    }

    public function show(Request $request, ServiceRequest $serviceRequest)
    {
        return new ServiceRequestResource($this->actions->show($serviceRequest, $request->user()));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id'     => ['required', 'integer', 'exists:services,id'],
            'price'          => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
        ]);
        $rec = $this->actions->store($data, $request->user());
        return (new ServiceRequestResource($rec))->response()->setStatusCode(201);
    }
}
