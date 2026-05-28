<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\Request as ServiceRequest;
use App\Repositories\Request\RequestRepo;
use Illuminate\Http\Request;

class AdminRequestActions
{
    public function __construct(protected RequestRepo $repo) {}

    public function index(Request $request)
    {
        $q = $this->repo->getModel()::query()->with('service');

        if ($s = $request->query('status_id'))    $q->where('status_id', (int) $s);
        if ($u = $request->query('user_id'))      $q->where('user_id', (int) $u);
        if ($sid = $request->query('service_id')) $q->where('service_id', (int) $sid);

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->latest()->paginate($perPage);
    }

    public function show(ServiceRequest $serviceRequest): ServiceRequest
    {
        return $serviceRequest->load('service');
    }

    public function update(ServiceRequest $serviceRequest, array $data): ServiceRequest
    {
        $serviceRequest->update($data);
        return $serviceRequest->fresh('service');
    }

    public function updateStatus(ServiceRequest $serviceRequest, int $statusId): ServiceRequest
    {
        $serviceRequest->update(['status_id' => $statusId]);
        return $serviceRequest->fresh('service');
    }

    public function destroy(ServiceRequest $serviceRequest): void
    {
        $serviceRequest->delete();
    }
}
