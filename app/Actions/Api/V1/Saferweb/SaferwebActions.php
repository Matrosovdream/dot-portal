<?php

namespace App\Actions\Api\V1\Saferweb;

use App\Models\VehicleCrashSaferweb;
use App\Models\VehicleInspectionSaferweb;
use App\Repositories\Vehicle\VehicleCrashesSaferwebRepo;
use App\Repositories\Vehicle\VehicleInspectionsSaferwebRepo;
use Illuminate\Http\Request;

class SaferwebActions
{
    public function __construct(
        private VehicleInspectionsSaferwebRepo $inspectionsRepo,
        private VehicleCrashesSaferwebRepo $crashesRepo,
    ) {
    }

    public function indexInspections(Request $request, $user)
    {
        $query = $this->inspectionsRepo->getModel()::query();

        $this->applyScope($query, $user);
        $this->applyFilters($query, $request);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($w) use ($search) {
                $w->where('unit_vin', 'like', "%{$search}%")
                    ->orWhere('report_number', 'like', "%{$search}%")
                    ->orWhere('dot_number', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('report_date')->paginate($this->perPage($request))->withQueryString();
    }

    public function showInspection(VehicleInspectionSaferweb $inspection, $user): void
    {
        $this->authorize($inspection, $user);
    }

    public function indexCrashes(Request $request, $user)
    {
        $query = $this->crashesRepo->getModel()::query();

        $this->applyScope($query, $user);
        $this->applyFilters($query, $request);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($w) use ($search) {
                $w->where('unit_vin', 'like', "%{$search}%")
                    ->orWhere('report_number', 'like', "%{$search}%")
                    ->orWhere('dot_number', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('report_date')->paginate($this->perPage($request))->withQueryString();
    }

    public function showCrash(VehicleCrashSaferweb $crash, $user): void
    {
        $this->authorize($crash, $user);
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 25);

        return $perPage > 0 ? min($perPage, 100) : 25;
    }

    private function applyFilters($query, Request $request): void
    {
        if ($state = trim((string) $request->query('report_state', ''))) {
            $query->where('report_state', $state);
        }

        if ($from = trim((string) $request->query('date_from', ''))) {
            $query->whereDate('report_date', '>=', $from);
        }

        if ($to = trim((string) $request->query('date_to', ''))) {
            $query->whereDate('report_date', '<=', $to);
        }
    }

    private function applyScope($query, $user): void
    {
        if ($user->isAdmin() || $user->isManager()) {
            return;
        }

        $companyId = $user->company?->id;
        $dotNumber = $user->company?->dot_number;

        if ($companyId === null && $dotNumber === null) {
            // No company context: return zero rows rather than leaking everything.
            $query->whereRaw('1 = 0');

            return;
        }

        $query->where(function ($q) use ($companyId, $dotNumber) {
            if ($companyId !== null) {
                $q->where('company_id', $companyId);
            }
            if ($dotNumber !== null) {
                $q->orWhere('dot_number', $dotNumber);
            }
        });
    }

    private function authorize($model, $user): void
    {
        if ($user->isAdmin() || $user->isManager()) {
            return;
        }

        $owns = ($model->company_id !== null && $model->company_id === $user->company?->id)
            || ($model->dot_number !== null && $model->dot_number === $user->company?->dot_number);

        if (! $owns) {
            abort(404);
        }
    }
}
