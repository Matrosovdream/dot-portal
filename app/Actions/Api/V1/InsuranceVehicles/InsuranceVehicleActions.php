<?php

namespace App\Actions\Api\V1\InsuranceVehicles;

use App\Models\InsuranceVehicle;
use App\Models\User;
use App\Repositories\Insurance\InsuranceVehicleRepo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InsuranceVehicleActions
{
    public function __construct(protected InsuranceVehicleRepo $repo) {}

    public function index(Request $request, User $auth)
    {
        $q = $this->repo->getModel()::query()->with('user');
        $this->applyScope($q, $auth);

        // Admin/manager may narrow the cross-company list to a single owner account.
        if (($auth->isAdmin() || $auth->isManager()) && ($ownerId = $request->query('user_id'))) {
            $q->where('user_id', (int) $ownerId);
        }

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->paginate($perPage);
    }

    public function store(array $data, User $auth): InsuranceVehicle
    {
        return $this->repo->getModel()::create($data + [
            'company_id' => $auth->company?->id,
            'user_id'    => $auth->id,
        ]);
    }

    public function show(InsuranceVehicle $insurance, User $auth): InsuranceVehicle
    {
        $this->authorize($auth, $insurance);
        return $insurance;
    }

    public function update(InsuranceVehicle $insurance, array $data, User $auth): InsuranceVehicle
    {
        $this->authorize($auth, $insurance);
        $insurance->update($data);
        return $insurance->fresh();
    }

    public function destroy(InsuranceVehicle $insurance, User $auth): void
    {
        $this->authorize($auth, $insurance);
        $insurance->delete();
    }

    private function applyScope(Builder $q, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) return;
        $companyId = $auth->company?->id;
        $q->where(function ($w) use ($companyId, $auth) {
            $w->where('company_id', $companyId)->orWhere('user_id', $auth->id);
        });
    }

    private function authorize(User $auth, InsuranceVehicle $insurance): void
    {
        if ($auth->isAdmin() || $auth->isManager()) return;
        $companyId = $auth->company?->id;
        if ($insurance->company_id === $companyId || $insurance->user_id === $auth->id) return;
        abort(404);
    }
}
