<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\AdminRequestActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ServiceRequests\ServiceRequestResource;
use App\Models\Request as ServiceRequest;
use Illuminate\Http\Request;

class AdminRequestController extends Controller
{
    public function __construct(protected AdminRequestActions $actions) {}

    public function index(Request $request)
    {
        return ServiceRequestResource::collection($this->actions->index($request));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        return new ServiceRequestResource($this->actions->show($serviceRequest));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate([
            'price'          => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'is_paid'        => ['nullable', 'boolean'],
        ]);
        return new ServiceRequestResource($this->actions->update($serviceRequest, $data));
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate(['status_id' => ['required', 'integer']]);
        return new ServiceRequestResource($this->actions->updateStatus($serviceRequest, (int) $data['status_id']));
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $this->actions->destroy($serviceRequest);
        return response()->noContent();
    }
}
