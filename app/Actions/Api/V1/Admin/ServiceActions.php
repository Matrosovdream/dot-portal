<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\Service;
use App\Repositories\Service\ServiceRepo;
use Illuminate\Http\Request;

class ServiceActions
{
    public function __construct(protected ServiceRepo $repo) {}

    public function index(Request $request)
    {
        $q = $this->repo->getModel()::query();
        if ($search = $request->query('q')) {
            $q->where('name', 'like', "%{$search}%");
        }
        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->paginate($perPage);
    }

    public function store(array $data): Service     { return $this->repo->getModel()::create($data); }
    public function show(Service $service): Service { return $service; }
    public function update(Service $service, array $data): Service
    {
        $service->update($data);
        return $service->fresh();
    }
    public function destroy(Service $service): void { $service->delete(); }
    public function updateStatus(Service $service, int $statusId): Service
    {
        $service->update(['status_id' => $statusId]);
        return $service->fresh();
    }
}
