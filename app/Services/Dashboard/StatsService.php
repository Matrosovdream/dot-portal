<?php

namespace App\Services\Dashboard;

use App\Repositories\Driver\DriverRepo;
use App\Repositories\Order\OrderRepo;
use App\Repositories\References\RefRequestStatusRepo;
use App\Repositories\Request\RequestRepo;
use App\Repositories\User\UserCompanyRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserTaskRepo;
use App\Repositories\Vehicle\VehicleRepo;

/**
 * Reusable dashboard statistics.
 *
 * Every method is scope-aware: pass a company/user id to scope it, or omit it for
 * a global (admin) figure. Role widget builders (see HomeActions) just pick the
 * stats they want from this menu — so adding stats for a new role is a one-liner,
 * and the heavy aggregation lives here once instead of being copy-pasted per role.
 *
 * KPI builders (kpi*) return a ready widget entry; chart builders return a
 * Chart.js-ready config the SPA can hand straight to <Chart>.
 */
class StatsService
{
    /** Request status slug treated as "open" (the only non-terminal status). */
    private const OPEN_REQUEST_SLUG = 'pending';

    /** Default look-back window for trends/time series. */
    private const WINDOW_DAYS = 30;

    /** Map the reference colour names to the SPA palette so charts stay on-brand. */
    private const COLOR_HEX = [
        'blue'   => '#1B84FF',
        'green'  => '#17C653',
        'red'    => '#F8285A',
        'orange' => '#F6B100',
        'yellow' => '#F6B100',
        'gray'   => '#99A1B7',
        'grey'   => '#99A1B7',
    ];

    private const ACCENT = '#1B84FF';
    private const ACCENT_REVENUE = '#17C653';

    public function __construct(
        protected UserRepo             $userRepo,
        protected UserCompanyRepo      $companyRepo,
        protected DriverRepo           $driverRepo,
        protected VehicleRepo          $vehicleRepo,
        protected RequestRepo          $requestRepo,
        protected OrderRepo            $orderRepo,
        protected UserTaskRepo         $taskRepo,
        protected RefRequestStatusRepo $requestStatusRepo,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | KPI builders — return a full widget entry {key,label,value,delta?,format?}
    |--------------------------------------------------------------------------
    */

    public function kpiUsers(): array
    {
        return $this->kpi('users', 'Total users', $this->userRepo->count(), $this->newCount($this->userRepo));
    }

    public function kpiCompanies(): array
    {
        return $this->kpi('companies', 'Companies', $this->companyRepo->count(), $this->newCount($this->companyRepo));
    }

    public function kpiDrivers(?int $companyId = null): array
    {
        $filter = $companyId ? ['company_id' => $companyId] : [];
        return $this->kpi('drivers', 'Drivers', $this->driverRepo->count($filter), $this->newCount($this->driverRepo, $filter));
    }

    public function kpiVehicles(?int $companyId = null): array
    {
        $filter = $companyId ? ['company_id' => $companyId] : [];
        return $this->kpi('vehicles', 'Vehicles', $this->vehicleRepo->count($filter), $this->newCount($this->vehicleRepo, $filter));
    }

    public function kpiOpenRequests(?int $userId = null): array
    {
        return $this->kpi('requests', 'Open requests', $this->openRequestCount($userId));
    }

    public function kpiRevenue(int $days = self::WINDOW_DAYS): array
    {
        return $this->kpi('revenue', "Revenue ({$days}d)", $this->revenue($days), null, 'currency');
    }

    /*
    |--------------------------------------------------------------------------
    | Raw figures — reusable primitives, scope-aware
    |--------------------------------------------------------------------------
    */

    public function openRequestCount(?int $userId = null): int
    {
        $statusId = $this->openStatusId();
        $filter = $statusId ? ['status_id' => $statusId] : [];
        if ($userId) {
            $filter['user_id'] = $userId;
        }
        return $this->requestRepo->count($filter);
    }

    public function revenue(int $days = self::WINDOW_DAYS): float
    {
        return array_sum($this->orderRepo->dailyCounts('created_at', $days, [], 'amount'));
    }

    public function taskSummary(int $userId): array
    {
        return [
            'open'    => $this->taskRepo->count(['user_id' => $userId, 'status' => 'pending']),
            'overdue' => $this->taskRepo->getModel()::query()
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
        ];
    }

    public function recentRequests(int $limit = 5, ?int $userId = null)
    {
        return $this->requestRepo->getModel()::query()
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->latest()
            ->limit($limit)
            ->get(['id', 'service_id', 'status_id', 'created_at']);
    }

    /*
    |--------------------------------------------------------------------------
    | Chart builders — return Chart.js-ready configs
    |--------------------------------------------------------------------------
    */

    /** Doughnut: request volume per status, coloured from the status reference. */
    public function requestsByStatus(?int $userId = null): array
    {
        $counts = $this->requestRepo->groupCount('status_id', $userId ? ['user_id' => $userId] : []);

        $labels = $data = $colors = [];
        foreach ($this->publishedStatuses() as $status) {
            $labels[] = $status->name;
            $data[]   = (int) ($counts[$status->id] ?? 0);
            $colors[] = self::COLOR_HEX[$status->color] ?? self::COLOR_HEX['gray'];
        }

        return [
            'key'      => 'requests_by_status',
            'type'     => 'doughnut',
            'title'    => 'Requests by status',
            'labels'   => $labels,
            'datasets' => [[
                'data'            => $data,
                'backgroundColor' => $colors,
            ]],
        ];
    }

    /** Line: requests created per day over the window. */
    public function requestsTimeSeries(int $days = self::WINDOW_DAYS, ?int $userId = null): array
    {
        $series = $this->requestRepo->dailyCounts('created_at', $days, $userId ? ['user_id' => $userId] : []);

        return $this->lineOrBar('requests_timeseries', 'line', "Requests (last {$days}d)", $series, 'Requests', self::ACCENT);
    }

    /** Bar: revenue booked per day over the window. */
    public function revenueTimeSeries(int $days = self::WINDOW_DAYS): array
    {
        $series = $this->orderRepo->dailyCounts('created_at', $days, [], 'amount');

        return $this->lineOrBar('revenue_timeseries', 'bar', "Revenue (last {$days}d)", $series, 'Revenue', self::ACCENT_REVENUE, 'currency');
    }

    /*
    |--------------------------------------------------------------------------
    | Internals
    |--------------------------------------------------------------------------
    */

    private function kpi(string $key, string $label, int|float $value, ?int $delta = null, ?string $format = null): array
    {
        $entry = ['key' => $key, 'label' => $label, 'value' => $value];
        if ($delta !== null) {
            $entry['delta'] = $delta;
        }
        if ($format !== null) {
            $entry['format'] = $format;
        }
        return $entry;
    }

    /** Rows created in the trailing window — drives the "+N" KPI badge. */
    private function newCount($repo, array $filter = [], int $days = self::WINDOW_DAYS): int
    {
        return (int) array_sum($repo->dailyCounts('created_at', $days, $filter));
    }

    private function lineOrBar(string $key, string $type, string $title, array $series, string $label, string $color, ?string $format = null): array
    {
        $config = [
            'key'      => $key,
            'type'     => $type,
            'title'    => $title,
            'labels'   => array_map(fn ($day) => date('M j', strtotime($day)), array_keys($series)),
            'datasets' => [[
                'label'           => $label,
                'data'            => array_values($series),
                'borderColor'     => $color,
                'backgroundColor' => $color,
                'tension'         => 0.35,
                'fill'            => false,
            ]],
        ];
        if ($format !== null) {
            $config['format'] = $format;
        }
        return $config;
    }

    private function publishedStatuses()
    {
        return $this->requestStatusRepo->getModel()::query()
            ->where('published', true)
            ->orderBy('id')
            ->get(['id', 'name', 'color']);
    }

    private function openStatusId(): ?int
    {
        return $this->requestStatusRepo->getModel()::query()
            ->where('slug', self::OPEN_REQUEST_SLUG)
            ->value('id');
    }
}
