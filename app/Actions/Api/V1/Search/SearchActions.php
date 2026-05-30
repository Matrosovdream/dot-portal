<?php

namespace App\Actions\Api\V1\Search;

use App\Models\Driver;
use App\Models\Request as ServiceRequest;
use App\Models\User;
use App\Models\Vehicle;

class SearchActions
{
    protected int $limit = 10;

    /**
     * Run the global search across the buckets the SPA topbar consumes.
     * Each bucket is scoped to what the authenticated user is allowed to see.
     *
     * @return array<string, \Illuminate\Database\Eloquent\Collection>
     */
    public function global(string $q, User $auth): array
    {
        return [
            'drivers'          => $this->drivers($q, $auth),
            'vehicles'         => $this->vehicles($q, $auth),
            'service_requests' => $this->serviceRequests($q, $auth),
        ];
    }

    protected function drivers(string $q, User $auth)
    {
        $query = Driver::query()->with('user');

        $this->scopeToCompany($query, $auth);

        $query->where(function ($w) use ($q) {
            $w->where('search_index', 'like', "%{$q}%")
              ->orWhereHas('user', function ($u) use ($q) {
                  $u->where('firstname', 'like', "%{$q}%")
                    ->orWhere('lastname', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
              });
        });

        return $query->latest()->limit($this->limit)->get();
    }

    protected function vehicles(string $q, User $auth)
    {
        $query = Vehicle::query()->with(['unitType', 'ownershipType', 'driver']);

        $this->scopeToCompany($query, $auth);

        $query->where(function ($w) use ($q) {
            $w->where('number', 'like', "%{$q}%")
              ->orWhere('vin', 'like', "%{$q}%")
              ->orWhere('search_index', 'like', "%{$q}%");
        });

        return $query->latest()->limit($this->limit)->get();
    }

    protected function serviceRequests(string $q, User $auth)
    {
        $query = ServiceRequest::query()->with('service');

        if (! ($auth->isAdmin() || $auth->isManager())) {
            $query->where('user_id', $auth->id);
        }

        $query->whereHas('service', function ($s) use ($q) {
            $s->where('name', 'like', "%{$q}%")
              ->orWhere('slug', 'like', "%{$q}%");
        });

        return $query->latest()->limit($this->limit)->get();
    }

    protected function scopeToCompany($query, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }

        $companyId = $auth->company?->id;

        $query->where(function ($w) use ($companyId, $auth) {
            $w->where('company_user_id', $auth->id);
            if ($companyId) {
                $w->orWhere('company_id', $companyId);
            }
        });
    }
}
