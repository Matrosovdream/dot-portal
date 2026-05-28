<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\PlanFee;
use App\Repositories\Subscription\PlanFeeRepo;
use Illuminate\Http\Request;

class PlanFeeActions
{
    public function __construct(protected PlanFeeRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = min(100, (int) $request->query('per_page', 25));
        return $this->repo->getModel()::query()->paginate($perPage);
    }

    public function store(array $data): PlanFee     { return $this->repo->getModel()::create($data); }
    public function show(PlanFee $fee): PlanFee     { return $fee; }
    public function update(PlanFee $fee, array $data): PlanFee
    {
        $fee->update($data);
        return $fee->fresh();
    }
}
