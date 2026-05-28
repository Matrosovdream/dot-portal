<?php

namespace App\Actions\Api\V1\Dashboard;

use App\Models\User;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\Request\RequestRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserTaskRepo;
use App\Repositories\Vehicle\VehicleRepo;

class HomeActions
{
    public function __construct(
        protected UserRepo     $userRepo,
        protected DriverRepo   $driverRepo,
        protected VehicleRepo  $vehicleRepo,
        protected RequestRepo  $requestRepo,
        protected UserTaskRepo $taskRepo,
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

    private function adminWidgets(): array
    {
        return [
            'kpis' => [
                ['key' => 'users',    'label' => 'Total users',   'value' => $this->userRepo->count()],
                ['key' => 'requests', 'label' => 'Open requests', 'value' => $this->requestRepo->count()],
                ['key' => 'drivers',  'label' => 'Drivers',       'value' => $this->driverRepo->count()],
                ['key' => 'vehicles', 'label' => 'Vehicles',      'value' => $this->vehicleRepo->count()],
            ],
            'recent_requests' => $this->requestRepo->getModel()::query()
                ->latest()->limit(5)->get(['id', 'service_id', 'status_id', 'created_at']),
        ];
    }

    private function companyWidgets(User $user): array
    {
        $companyId = $user->company?->id;

        $driversTotal  = $this->driverRepo->count(['company_id' => $companyId]);
        $vehiclesTotal = $this->vehicleRepo->count(['company_id' => $companyId]);

        return [
            'kpis' => [
                ['key' => 'drivers',  'label' => 'Drivers',  'value' => $driversTotal],
                ['key' => 'vehicles', 'label' => 'Vehicles', 'value' => $vehiclesTotal],
            ],
            'todo_summary' => $this->todoSummary($user),
            'banner_new_company' => $driversTotal === 0 || $vehiclesTotal === 0,
        ];
    }

    private function driverWidgets(User $user): array
    {
        return [
            'todo_summary' => $this->todoSummary($user),
        ];
    }

    private function todoSummary(User $user): array
    {
        return [
            'open'    => $this->taskRepo->count(['user_id' => $user->id, 'status' => 'pending']),
            'overdue' => $this->taskRepo->getModel()::query()
                ->where('user_id', $user->id)->where('status', 'pending')
                ->where('due_date', '<', now())->count(),
        ];
    }
}
