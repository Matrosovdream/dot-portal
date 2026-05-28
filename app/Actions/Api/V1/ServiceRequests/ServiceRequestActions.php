<?php

namespace App\Actions\Api\V1\ServiceRequests;

use App\Models\Request as ServiceRequest;
use App\Models\User;
use App\Repositories\Request\RequestRepo;
use App\Repositories\Service\ServiceRepo;
use Illuminate\Http\Request;

class ServiceRequestActions
{
    public function __construct(
        protected RequestRepo $requestRepo,
        protected ServiceRepo $serviceRepo,
    ) {}

    public function index(Request $request, User $user)
    {
        $q = $this->requestRepo->getModel()::query()->with('service')->where('user_id', $user->id);

        if ($status = $request->query('status_id')) {
            $q->where('status_id', (int) $status);
        }

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->latest()->paginate($perPage);
    }

    public function show(ServiceRequest $req, User $user): ServiceRequest
    {
        $this->authorize($user, $req);
        return $req->load('service');
    }

    public function store(array $data, User $user): ServiceRequest
    {
        $service = $this->serviceRepo->getModel()::find($data['service_id']);
        $price   = $data['price'] ?? ($service->price ?? 0);

        return $this->requestRepo->getModel()::create([
            'user_id'        => $user->id,
            'service_id'     => $data['service_id'],
            'status_id'      => 1,
            'is_paid'        => false,
            'price'          => $price,
            'discount_price' => $data['discount_price'] ?? 0,
        ])->load('service');
    }

    private function authorize(User $auth, ServiceRequest $req): void
    {
        if ($auth->isAdmin() || $auth->isManager()) return;
        if ($req->user_id === $auth->id) return;
        abort(404);
    }
}
