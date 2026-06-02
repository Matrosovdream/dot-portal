<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\SubscriptionCustomRequest;
use App\Repositories\Subscription\SubscriptionRequestRepo;
use Illuminate\Http\Request;

class SubRequestActions
{
    public function __construct(protected SubscriptionRequestRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = min(100, (int) $request->query('per_page', 25));

        $query = $this->repo->getModel()::query();

        if ($statusId = $request->query('status_id')) {
            $query->where('status_id', (int) $statusId);
        }

        return $query->paginate($perPage);
    }

    public function store(array $data): SubscriptionCustomRequest
    {
        return $this->repo->getModel()::create($data + ['status_id' => $data['status_id'] ?? 1]);
    }

    public function show(SubscriptionCustomRequest $req): SubscriptionCustomRequest { return $req; }

    public function update(SubscriptionCustomRequest $req, array $data): SubscriptionCustomRequest
    {
        $req->update($data);
        return $req->fresh();
    }

    public function destroy(SubscriptionCustomRequest $req): void { $req->delete(); }
    public function sendEmail(SubscriptionCustomRequest $req): void { /* step 13 wires actual mail */ }
}
