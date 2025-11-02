@extends('dashboard.layouts.app')

@section('toolbar-buttons')
    {{-- Optional quick actions (can be removed if you prefer only the summary buttons) --}}
    <a href="{{ route('dashboard.clearinghouse.buyqueries.index') }}" class="btn btn-sm fw-bold btn-light-primary">
        <i class="ki-duotone ki-search-list me-2"><span class="path1"></span><span class="path2"></span></i>
        Buy Queries
    </a>
    @if(empty($company['clearinghouse_registered']))
        <a href="{{ route('dashboard.clearinghouse.registercompany') }}" class="btn btn-sm fw-bold btn-primary">
            <i class="ki-duotone ki-shield-tick me-2"><span class="path1"></span><span class="path2"></span></i>
            Register Company
        </a>
    @endif
@endsection

@section('content')

<div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10" style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('assets/media/illustrations/sketchy-1/4.png')">
 
    <div class="card-body pt-10">
    
    <div class="row g-6 align-items-center">
        <div class="col-md-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-4">
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-abstract-39 fs-2 text-primary">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div>
                    <div class="text-muted fs-7">Company</div>
                    <div class="fs-5 fw-bold">
                        {{ $company['name'] ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-4">
                    <div class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-shield-tick fs-2 text-success">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <div class="text-muted fs-7">Clearinghouse Enrollment</div>
                                                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-light">Not Registered</span>
                            <a href="http://127.0.0.1:8000/dashboard/clearing-house/register-company" class="btn btn-sm btn-primary">Register Company</a>
                        </div>
                                                
                    </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-4">
                    <div class="symbol-label bg-light-info">
                        <i class="ki-duotone ki-search-list fs-2 text-info">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <div class="text-muted fs-7">Query Balance</div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="fs-5 fw-bold">0</div>
                        <span class="text-muted">remaining</span>
                        <a href="http://127.0.0.1:8000/dashboard/clearing-house/buy-queries" class="btn btn-sm btn-light-info">Buy Queries</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

    {{-- Page Card --}}
    <div class="card card-flush">

        {{-- Banner: CDL drivers not enrolled --}}
        @php
            $hasCdlNotEnrolled = ($issues['cdl_not_enrolled'] ?? 0) > 0;
        @endphp
        @if($hasCdlNotEnrolled)
            <div class="px-6">
                <div class="alert alert-warning d-flex align-items-center p-5">
                    <i class="ki-duotone ki-information-5 fs-1 me-4">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1">You have CDL drivers. Register in Clearinghouse.</h4>
                        <span>
                            <a href="#roster" class="fw-bold">Go to Enrollment / Roster</a>
                        </span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Header Summary (Company + Enrollment + Query Balance) --}}
        <div class="card-body pt-0 mt-5">




            {{-- Driver Roster --}}
            <div id="roster" class="table-responsive">

                @if(empty($drivers['items']) || count($drivers['items']) === 0)
                    <div class="text-center mt-10">
                        <h4>No drivers found</h4>
                    </div>
                @else

                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>Driver</th>
                            <th>CDL</th>
                            <th>Clearinghouse</th>
                            <th>Consent Status</th>
                            <th>Last Query</th>
                            <th>Random Testing Pool</th>
                            <th class="min-w-200px text-center">Actions</th>
                        </tr>
                        </thead>

                        <tbody class="fw-semibold text-gray-600">
                        @foreach($drivers['items'] as $driver)
                            @php
                                // Normalize data (adjust to your actual structure)
                                $driverName     = trim(($driver['firstname'] ?? '').' '.($driver['lastname'] ?? '')) ?: ($driver['name'] ?? '—');
                                $isCdl          = (bool)($driver['is_cdl'] ?? false);
                                $chEnrolled     = (bool)($driver['clearinghouse_enrolled'] ?? false);
                                $randPool       = (bool)($driver['random_pool'] ?? false);
                                $consent        = $driver['consent_status'] ?? 'not_given'; // pending|not_given|accepted|passed|violation
                                $lastQueryType  = $driver['last_query']['type'] ?? null;    // limited|full|null
                                $lastQueryRes   = $driver['last_query']['result'] ?? null;  // Clear|Violation|N/A|null

                                $consentMap = [
                                    'pending'    => ['label'=>'Pending',               'class'=>'badge-light-warning'],
                                    'not_given'  => ['label'=>'Consent Not Given',     'class'=>'badge-light'],
                                    'accepted'   => ['label'=>'Accepted / Completed',  'class'=>'badge-light-primary'],
                                    'passed'     => ['label'=>'Passed / No Record',    'class'=>'badge-light-success'],
                                    'violation'  => ['label'=>'Violation Found',       'class'=>'badge-light-danger'],
                                ];
                                $cMeta = $consentMap[$consent] ?? $consentMap['not_given'];
                            @endphp

                            <tr>
                                {{-- Driver + inline badges --}}
                                <td>
                                    <span class="d-inline-flex flex-column">
                                        @if(!empty($driver['id']))
                                            <a href="{{ route('dashboard.drivers.show', $driver['id']) }}"
                                               class="text-gray-800 text-hover-primary fs-6 fw-bold me-1">
                                                {{ $driverName }}
                                            </a>
                                        @else
                                            <span class="fs-6 fw-bold">{{ $driverName }}</span>
                                        @endif
                                        <span class="mt-1">
                                            @if($chEnrolled)
                                                <span class="badge badge-light-success me-1">Clearinghouse Enrolled</span>
                                            @endif
                                            @if($randPool)
                                                <span class="badge badge-light-info">Random Pool</span>
                                            @endif
                                            @if($isCdl && !$chEnrolled)
                                                <span class="badge badge-light-danger">Not Enrolled</span>
                                            @endif
                                        </span>
                                    </span>
                                </td>

                                {{-- CDL --}}
                                <td>
                                    <span class="badge {{ $isCdl ? 'badge-light-primary' : 'badge-light' }}">
                                        {{ $isCdl ? 'Yes' : 'No' }}
                                    </span>
                                </td>

                                {{-- Clearinghouse --}}
                                <td>
                                    <span class="badge {{ $chEnrolled ? 'badge-light-success' : 'badge-light' }}">
                                        {{ $chEnrolled ? 'Yes' : 'No' }}
                                    </span>
                                </td>

                                {{-- Consent Status --}}
                                <td>
                                    <span class="badge {{ $cMeta['class'] }}">{{ $cMeta['label'] }}</span>
                                </td>

                                {{-- Last Query --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-muted fs-7">
                                            {{ $lastQueryType ? ( $lastQueryType === 'full' ? 'Full' : 'Limited') : '—' }}
                                        </span>
                                        <span class="fw-bold">{{ $lastQueryRes ?? '—' }}</span>
                                    </div>
                                </td>

                                {{-- Random Testing Pool --}}
                                <td>
                                    <span class="badge {{ $randPool ? 'badge-light-info' : 'badge-light' }}">
                                        {{ $randPool ? 'Yes' : 'No' }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                       data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600
                                                menu-state-bg-light-primary fw-semibold fs-7 w-225px py-4"
                                         data-kt-menu="true">

                                        {{-- Enroll in CH --}}
                                        <div class="menu-item px-3">
                                            @if(!$chEnrolled)
                                                <form action="#"
                                                      method="POST">
                                                    @csrf
                                                    <button class="menu-link px-3 w-100 text-start" type="submit">
                                                        Enroll in CH
                                                    </button>
                                                </form>
                                            @else
                                                <span class="menu-link px-3 disabled">Enroll in CH</span>
                                            @endif
                                        </div>

                                        {{-- Send Consent --}}
                                        <div class="menu-item px-3">
                                            <form action="#"
                                                  method="POST">
                                                @csrf
                                                <button class="menu-link px-3 w-100 text-start" type="submit">
                                                    Send Consent
                                                </button>
                                            </form>
                                        </div>

                                        <div class="menu-separator my-1"></div>

                                        {{-- Run Limited --}}
                                        <div class="menu-item px-3">
                                            <form action="#"
                                                  method="POST">
                                                @csrf
                                                <button class="menu-link px-3 w-100 text-start" type="submit">
                                                    Run Limited
                                                </button>
                                            </form>
                                        </div>

                                        {{-- Run Full --}}
                                        <div class="menu-item px-3">
                                            <form action="#"
                                                  method="POST">
                                                @csrf
                                                <button class="menu-link px-3 w-100 text-start" type="submit">
                                                    Run Full
                                                </button>
                                            </form>
                                        </div>

                                        <div class="menu-separator my-1"></div>

                                        {{-- Schedule Test --}}
                                        <div class="menu-item px-3">
                                            <a href="#"
                                               class="menu-link px-3">Schedule Test</a>
                                        </div>

                                        {{-- Enroll in Random Pool --}}
                                        <div class="menu-item px-3">
                                            @if(!$randPool)
                                                <form action="#"
                                                      method="POST">
                                                    @csrf
                                                    <button class="menu-link px-3 w-100 text-start" type="submit">
                                                        Enroll in Random Pool
                                                    </button>
                                                </form>
                                            @else
                                                <span class="menu-link px-3 disabled">Enroll in Random Pool</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                @endif

            </div>

            {{-- Pagination --}}
            <div class="row">
                {{ $drivers['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
            </div>

        </div>
        {{-- /card-body --}}
    </div>
    {{-- /card --}}

@endsection
