<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\RefServiceGroup;
use App\Repositories\References\RefServiceGroupRepo;
use Illuminate\Http\Request;

class ServiceGroupActions
{
    public function __construct(protected RefServiceGroupRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = min(100, (int) $request->query('per_page', 50));
        return $this->repo->getModel()::query()->paginate($perPage);
    }

    public function store(array $data): RefServiceGroup       { return $this->repo->getModel()::create($data); }
    public function show(RefServiceGroup $group): RefServiceGroup { return $group; }
    public function update(RefServiceGroup $group, array $data): RefServiceGroup
    {
        $group->update($data);
        return $group->fresh();
    }
    public function destroy(RefServiceGroup $group): void     { $group->delete(); }
}
