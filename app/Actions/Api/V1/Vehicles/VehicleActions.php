<?php

namespace App\Actions\Api\V1\Vehicles;

use App\Models\User;
use App\Models\Vehicle;
use App\Repositories\Vehicle\VehicleRepo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VehicleActions
{
    public function __construct(protected VehicleRepo $vehicleRepo) {}

    public function index(Request $request, User $auth)
    {
        $q = $this->vehicleRepo->getModel()::query()->with('owner');
        $this->applyScope($q, $auth);

        // Admin/manager may narrow the cross-company list to a single owner account.
        if (($auth->isAdmin() || $auth->isManager()) && ($ownerId = $request->query('user_id'))) {
            $q->where('company_user_id', (int) $ownerId);
        }

        if ($search = $request->query('q')) {
            $like = '%'.$search.'%';
            $q->where(fn ($w) => $w->where('number', 'like', $like)->orWhere('vin', 'like', $like));
        }
        if ($t = $request->query('unit_type_id'))      $q->where('unit_type_id', (int) $t);
        if ($o = $request->query('ownership_type_id')) $q->where('ownership_type_id', (int) $o);

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->paginate($perPage);
    }

    public function store(array $data, User $auth): Vehicle
    {
        return $this->vehicleRepo->getModel()::create($data + [
            'company_id'      => $auth->company?->id,
            'company_user_id' => $auth->id,
            'is_finished'     => false,
        ]);
    }

    public function show(Vehicle $vehicle, User $auth): Vehicle
    {
        $this->authorize($auth, $vehicle);
        return $vehicle;
    }

    public function update(Vehicle $vehicle, array $data, User $auth): Vehicle
    {
        $this->authorize($auth, $vehicle);
        $vehicle->update($data);
        return $vehicle->fresh();
    }

    public function destroy(Vehicle $vehicle, User $auth): void
    {
        $this->authorize($auth, $vehicle);
        $vehicle->delete();
    }

    private function applyScope(Builder $q, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) return;
        $companyId = $auth->company?->id;
        $q->where(function ($w) use ($companyId, $auth) {
            $w->where('company_id', $companyId)
              ->orWhere('company_user_id', $auth->id);
        });
    }

    private function authorize(User $auth, Vehicle $vehicle): void
    {
        if ($auth->isAdmin() || $auth->isManager()) return;
        $companyId = $auth->company?->id;
        if ($vehicle->company_id === $companyId || $vehicle->company_user_id === $auth->id) return;
        abort(404);
    }
}
