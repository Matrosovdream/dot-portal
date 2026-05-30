<?php

namespace App\Actions\Api\V1\Admin\Users;

use App\Models\User;
use App\Repositories\User\UserRepo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserActions
{
    public function __construct(
        protected UserRepo $userRepo,
    ) {
    }

    public function index(Request $request): LengthAwarePaginator
    {
        $perPage = min(100, (int) $request->query('per_page', 25));

        $query = $this->userRepo->getModel()::query()->with('roles');

        if ($q = trim((string) $request->query('q', ''))) {
            $query->where(function ($sub) use ($q) {
                $sub->where('firstname', 'like', "%{$q}%")
                    ->orWhere('lastname', 'like', "%{$q}%")
                    ->orWhere('fullname', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        return $query->latest('id')->paginate($perPage);
    }

    public function store(Request $request): User
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            // password is assigned PLAINTEXT; the model 'hashed' cast hashes it once.
            // fullname is intentionally omitted: User::booted() derives it.
            $user = $this->userRepo->getModel()::query()->create([
                'firstname' => $data['firstname'],
                'lastname'  => $data['lastname'] ?? null,
                'phone'     => $data['phone'] ?? null,
                'birthday'  => $data['birthday'] ?? null,
                'email'     => $data['email'],
                'password'  => $data['password'],
                'is_active' => true,
            ]);

            // setRole() syncs (replaces) roles by slug; slug existence guaranteed by request rules.
            $user->setRole($data['role']);

            return $user->load('roles');
        });
    }

    public function show(Request $request, User $user): User
    {
        return $user->load('roles');
    }

    public function update(Request $request, User $user): User
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data, $user) {
            $fillable = array_intersect_key(
                $data,
                array_flip(['firstname', 'lastname', 'phone', 'birthday', 'email'])
            );

            if (! empty($fillable)) {
                $user->update($fillable);
            }

            if (array_key_exists('password', $data) && $data['password'] !== null && $data['password'] !== '') {
                // assign PLAINTEXT; model 'hashed' cast hashes once.
                $user->password = $data['password'];
                $user->save();
            }

            if (array_key_exists('role', $data) && $data['role'] !== null) {
                $user->setRole($data['role']);
            }

            return $user->fresh()->load('roles');
        });
    }

    public function destroy(Request $request, User $user): void
    {
        DB::transaction(function () use ($user) {
            // user_roles has no ON DELETE cascade; detach pivot rows first.
            $user->roles()->detach();
            $user->delete();
        });
    }
}
