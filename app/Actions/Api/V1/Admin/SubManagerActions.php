<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\UserSubscription;
use App\Repositories\User\UserSubscriptionRepo;
use Illuminate\Http\Request;

class SubManagerActions
{
    public function __construct(protected UserSubscriptionRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = min(100, (int) $request->query('per_page', 25));
        return $this->repo->getModel()::query()->with('subscription')->paginate($perPage);
    }

    public function store(array $data): UserSubscription
    {
        return $this->repo->getModel()::create($data + [
            'status' => $data['status'] ?? 'active',
            'start_date' => now(),
        ]);
    }

    public function show(UserSubscription $sub): UserSubscription { return $sub->load('subscription'); }

    public function update(UserSubscription $sub, array $data): UserSubscription
    {
        $sub->update($data);
        return $sub->fresh('subscription');
    }

    public function destroy(UserSubscription $sub): void { $sub->delete(); }

    public function sendOnetime(UserSubscription $sub): void { /* step 13 wires actual mail */ }
    public function sendPaymentLink(UserSubscription $sub): void { /* step 13 wires actual mail */ }
}
