<?php

namespace App\Actions\Api\V1\Dashboard;

use App\Models\User;
use App\Services\Dashboard\StatsService;

class HomeActions
{
    private const WINDOW_DAYS = 30;

    public function __construct(
        protected StatsService $stats,
    ) {}

    public function show(User $user): array
    {
        $role = $this->roleFor($user);

        return [
            'role'    => $role,
            'widgets' => $this->widgetsFor($role, $user),
        ];
    }

    private function roleFor(User $user): string
    {
        return match (true) {
            $user->isAdmin()   => 'admin',
            $user->isManager() => 'manager',
            $user->isCompany() => 'company',
            $user->isDriver()  => 'driver',
            default            => 'company',
        };
    }

    private function widgetsFor(string $role, User $user): array
    {
        return match ($role) {
            'admin', 'manager' => $this->adminWidgets(),
            'company'          => $this->companyWidgets($user),
            'driver'           => $this->driverWidgets($user),
        };
    }

    /**
     * Each role just picks the stats it needs from StatsService — no query logic here.
     */
    private function adminWidgets(): array
    {
        return [
            'kpis' => [
                $this->stats->kpiUsers(),
                $this->stats->kpiCompanies(),
                $this->stats->kpiOpenRequests(),
                $this->stats->kpiDrivers(),
                $this->stats->kpiVehicles(),
                $this->stats->kpiRevenue(),
            ],
            'charts' => [
                $this->stats->requestsByStatus(),
                $this->stats->requestsTimeSeries(),
                $this->stats->revenueTimeSeries(),
            ],
            'recent_requests' => $this->stats->recentRequests(),
        ];
    }

    private function companyWidgets(User $user): array
    {
        $companyId = $user->company?->id;

        $driversTotal  = $this->stats->kpiDrivers($companyId);
        $vehiclesTotal = $this->stats->kpiVehicles($companyId);

        return [
            'kpis' => [
                $driversTotal,
                $vehiclesTotal,
                $this->stats->kpiOpenRequests($user->id),
            ],
            'charts' => [
                $this->stats->requestsByStatus($user->id),
                $this->stats->requestsTimeSeries(self::WINDOW_DAYS, $user->id),
            ],
            'recent_requests'    => $this->stats->recentRequests(5, $user->id),
            'todo_summary'       => $this->stats->taskSummary($user->id),
            'banner_new_company' => $driversTotal['value'] === 0 || $vehiclesTotal['value'] === 0,
        ];
    }

    private function driverWidgets(User $user): array
    {
        // Documents hang off the driver's Driver row (Driver.user_id = User.id), not the User.
        $driverId = $user->driver?->id;

        return [
            'kpis' => [
                $this->stats->kpiDocuments($driverId),
            ],
            'recent_documents' => $this->stats->recentDocuments($driverId),
            'recent_tasks'     => $this->stats->recentTasks($user->id),
            'todo_summary'     => $this->stats->taskSummary($user->id),
        ];
    }
}
