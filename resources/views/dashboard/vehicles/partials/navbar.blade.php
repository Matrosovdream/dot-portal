@php

    $sections = [
        'overview' => [
            'title' => 'Overview',
            'url' => route('dashboard.vehicles.show', $vehicle['id']),
            'alert' => null,
        ],
        'general' => [
            'title' => 'Vehicle information',
            'url' => route('dashboard.vehicles.show.profile', $vehicle['id']),
            'alert' => $validation['errors']['general'] ?? null,
        ],
        'mvr' => [
            'title' => 'MVR',
            'url' => route('dashboard.vehicles.show.mvr', $vehicle['id']),
            'alert' => $validation['errors']['mvr'] ?? null,
        ],
        'insurance' => [
            'title' => 'Insurance',
            'url' => route('dashboard.vehicles.show.insurance', $vehicle['id']),
            'alert' => $validation['errors']['insurance'] ?? null,
        ],
        'inspections' => [
            'title' => 'Inspections',
            'url' => route('dashboard.vehicles.show.inspections', $vehicle['id']),
            'alert' => $validation['errors']['inspections'] ?? null,
        ],
        'driverhistory' => [
            'title' => 'Drivers history',
            'url' => route('dashboard.vehicles.show.driverhistory', $vehicle['id']),
            'alert' => $validation['errors']['inspections'] ?? null,
        ],
        /*'logs' => [
            'title' => 'Logs',
            'url' => route('dashboard.vehicles.show.logs', $vehicle['id']),
            'alert' => null,
        ],*/
    ];

@endphp


<div class="d-flex flex-wrap flex-sm-nowrap">

    <div class="me-7 mb-4">
        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">

            @if( isset( $vehicle['profilePhoto']['showUrl'] ) )
                <img src="{{ $vehicle['profilePhoto']['showUrl'] }}" alt="image" />
            @else
                <img src="{{ asset('assets/admin/media/avatars/300-2.png') }}" alt="image" />
            @endif    

            @if( $validation['valid'] )
                <div
                    class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                </div>
            @else
                <div
                    class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-danger rounded-circle border border-4 border-body h-20px w-20px">
                </div>
            @endif
        </div>
    </div>

    <div class="flex-grow-1">

        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
            <div class="d-flex flex-column">

                <div class="d-flex align-items-center mb-2">
                    <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                        Vehicle Vin#{{ $vehicle['vin'] ?? '' }}
                    </a>
                    <a href="#">
                        <i class="ki-duotone ki-verify fs-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                </div>

                @php /*
                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                    <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                        <i class="ki-duotone ki-profile-circle fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Driver
                    </a>
                    <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                        <i class="ki-duotone ki-geolocation fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        {{ $vehicle['address']['city'] ?? '' }}, {{ $vehicle['address']['zip'] ?? '' }}
                    </a>
                    <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                        <i class="ki-duotone ki-sms fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        {{ $vehicle['email'] ?? '' }}
                    </a>
                </div>
                */ @endphp

            </div>

            <div class="d-flex my-4">
                <a href="#" class="btn btn-sm btn-light me-2" id="kt_user_follow_button">
                    <i class="ki-duotone ki-check fs-3 d-none"></i>
                    <span class="indicator-label">
                        Disable
                    </span>
                </a>
                <a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_offer_a_deal">
                    Update from API
                </a>

                <div class="me-0">
                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                    </button>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                        data-kt-menu="true">

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link flex-stack px-3">Create Payment
                                <span class="ms-2" data-bs-toggle="tooltip"
                                    aria-label="Specify a target name for future usage and reference"
                                    data-bs-original-title="Specify a target name for future usage and reference"
                                    data-kt-initialized="1">
                                    <i class="ki-duotone ki-information fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span></a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="d-flex flex-wrap flex-stack">
            <div class="d-flex flex-column flex-grow-1 pe-8">
                <div class="d-flex flex-wrap">
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">

                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-arrow-up fs-3 text-success me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="4500"
                                data-kt-countup-prefix="$">0</div>
                        </div>

                        <div class="fw-semibold fs-6 text-gray-500">Earnings</div>

                    </div>

                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">

                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-arrow-down fs-3 text-danger me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="80">0
                            </div>
                        </div>

                        <div class="fw-semibold fs-6 text-gray-500">Projects</div>

                    </div>

                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                        <!--begin::Number-->
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-arrow-up fs-3 text-success me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="60"
                                data-kt-countup-prefix="%">0</div>
                        </div>

                        <div class="fw-semibold fs-6 text-gray-500">Success Rate</div>

                    </div>

                </div>
            </div>

            <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                    <span class="fw-semibold fs-6 text-gray-500">Profile Completion</span>
                    <span class="fw-bold fs-6">
                        {{ $validation['percent'] ?? 0 }}%
                    </span>
                </div>
                <div class="h-5px mx-3 w-100 bg-light mb-3">
                    <div 
                        class="bg-success rounded h-5px" 
                        role="progressbar" 
                        style="width: {{ $validation['percent'] ?? 0 }}%;" 
                        aria-valuenow="{{ $validation['percent'] ?? 0 }}"
                        aria-valuemin="0" 
                        aria-valuemax="100"
                        ></div>
                </div>
            </div>

        </div>

    </div>

</div>

<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">

    @foreach ($sections as $key => $section)
        <li class="nav-item">
            <a 
                class="nav-link text-active-primary py-5 
                {{ request()->url() == $section['url'] ? 'active' : '' }}
                " 
                href="{{ $section['url'] }}"
                >
                {{ $section['title'] }}

                @if( $section['alert'] )
                    
                    <i class="ki-duotone ki-information fs-2x text-warning  me-4" style="margin-left: 5px;">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>

                @endif

            </a>
        </li>
    @endforeach

</ul>