<?php

namespace App\Actions\Api\V1\Admin\Companies;

use App\Models\UserCompany;
use App\Repositories\User\UserCompanyRepo;
use Illuminate\Http\Request;

class AdminCompanyActions
{
    public function __construct(protected UserCompanyRepo $repo) {}

    public function index(Request $request)
    {
        $q = $this->repo->getModel()::query()->with('user');

        if ($search = trim((string) $request->query('q', ''))) {
            $like = '%'.$search.'%';
            $q->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                  ->orWhere('dot_number', 'like', $like)
                  ->orWhere('mc_number', 'like', $like);
            });
        }

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->latest('id')->paginate($perPage);
    }

    public function store(array $data): UserCompany
    {
        return $this->repo->getModel()::create($data)->load('user');
    }

    public function show(UserCompany $company): UserCompany
    {
        return $company->load('user');
    }

    public function update(UserCompany $company, array $data): UserCompany
    {
        $company->update($data);

        return $company->fresh()->load('user');
    }

    public function destroy(UserCompany $company): void
    {
        $company->delete();
    }
}
