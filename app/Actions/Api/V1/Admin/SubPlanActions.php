<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\Subscription;
use App\Repositories\Subscription\SubscriptionRepo;
use Illuminate\Http\Request;

class SubPlanActions
{
    public function __construct(protected SubscriptionRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = min(100, (int) $request->query('per_page', 25));
        return $this->repo->getModel()::query()->paginate($perPage);
    }

    public function store(array $data): Subscription      { return $this->repo->getModel()::create($data); }
    public function show(Subscription $plan): Subscription { return $plan; }
    public function update(Subscription $plan, array $data): Subscription
    {
        $plan->update($data);
        return $plan->fresh();
    }
    public function destroy(Subscription $plan): void      { $plan->delete(); }
}
