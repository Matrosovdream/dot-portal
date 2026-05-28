<?php

namespace App\Actions\Api\V1\Drivers;

use App\Models\Driver;
use App\Models\User;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\User\UserRepo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DriverActions
{
    public function __construct(
        protected DriverRepo $driverRepo,
        protected UserRepo   $userRepo,
    ) {}

    public function index(Request $request, User $auth)
    {
        $q = $this->driverRepo->getModel()::query()->with('user');
        $this->applyScope($q, $auth);

        if ($status = $request->query('status')) {
            $statusId = match ($status) {
                'active'     => 1,
                'inactive'   => 2,
                'terminated' => 3,
                default      => null,
            };
            if ($statusId) $q->where('status_id', $statusId);
        }

        if ($search = $request->query('q')) {
            $like = '%'.$search.'%';
            $q->whereHas('user', fn ($u) => $u
                ->where('firstname', 'like', $like)
                ->orWhere('lastname', 'like', $like)
                ->orWhere('email', 'like', $like));
        }

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->paginate($perPage);
    }

    public function store(array $data, User $auth): Driver
    {
        return DB::transaction(function () use ($data, $auth) {
            $newUser = $this->userRepo->getModel()::create([
                'firstname' => $data['firstname'],
                'lastname'  => $data['lastname'],
                'phone'     => $data['phone'] ?? null,
                'email'     => $data['email'],
                'password'  => Hash::make($data['password'] ?? Str::random(16)),
                'is_active' => true,
                'reg_step'  => null,
            ]);
            $newUser->setRole('driver');

            return $this->driverRepo->getModel()::create([
                'user_id'        => $newUser->id,
                'company_id'     => $auth->company?->id,
                'company_user_id'=> $auth->id,
                'dob'            => $data['dob'] ?? null,
                'ssn'            => $data['ssn'] ?? null,
                'hire_date'      => $data['hire_date'] ?? null,
                'driver_type_id' => $data['driver_type_id'] ?? null,
                'status_id'      => 1,
                'is_finished'    => false,
            ]);
        });
    }

    public function show(Driver $driver, User $auth): Driver
    {
        $this->authorize($auth, $driver);
        return $driver->load('user');
    }

    public function update(Driver $driver, array $data, User $auth): Driver
    {
        $this->authorize($auth, $driver);

        $userFields   = array_intersect_key($data, array_flip(['firstname','middlename','lastname','phone','email']));
        $driverFields = array_intersect_key($data, array_flip(['dob','ssn','hire_date','driver_type_id']));

        DB::transaction(function () use ($driver, $userFields, $driverFields) {
            if ($userFields && $driver->user) {
                $driver->user->update($userFields);
            }
            if ($driverFields) {
                $driver->update($driverFields);
            }
        });

        return $driver->fresh()->load('user');
    }

    public function destroy(Driver $driver, User $auth): void
    {
        $this->authorize($auth, $driver);
        $driver->delete();
    }

    public function terminate(Driver $driver, User $auth): Driver
    {
        $this->authorize($auth, $driver);
        $driver->update(['status_id' => 3]);
        return $driver->fresh()->load('user');
    }

    public function sendOnetime(Driver $driver, User $auth): void
    {
        $this->authorize($auth, $driver);
        // Step 13 wires actual mail dispatch.
    }

    private function applyScope(Builder $q, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }
        $companyId = $auth->company?->id;
        $q->where(function ($w) use ($companyId, $auth) {
            $w->where('company_id', $companyId)
              ->orWhere('company_user_id', $auth->id);
        });
    }

    private function authorize(User $auth, Driver $driver): void
    {
        if ($auth->isAdmin() || $auth->isManager()) return;
        $companyId = $auth->company?->id;
        if ($driver->company_id === $companyId || $driver->company_user_id === $auth->id) return;
        abort(404);
    }
}
