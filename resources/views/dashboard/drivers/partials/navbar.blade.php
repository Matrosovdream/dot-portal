@php

    $sections = [
        'overview' => [
            'title' => 'Overview',
            'url' => route('dashboard.drivers.show', $driver['id']),
            'alert' => null,
        ],
        'general' => [
            'title' => 'Profile information',
            'url' => route('dashboard.drivers.show.profile', $driver['id']),
            'alert' => $validation['errors']['general'] ?? null,
        ],
        'license' => [
            'title' => 'Driver license',
            'url' => route('dashboard.drivers.show.license', $driver['id']),
            'alert' => $validation['errors']['license'] ?? null,
        ],
        'cdl_license' => [
            'title' => 'CDL license',
            'url' => route('dashboard.drivers.show.cdl-license', $driver['id']),
            'alert' => $validation['errors']['cdlLicense'] ?? null,
        ],
        'address' => [
            'title' => 'Address',
            'url' => route('dashboard.drivers.show.address', $driver['id']),
            'alert' => $validation['errors']['address'] ?? null,
        ],
        'medical' => [
            'title' => 'Medical card',
            'url' => route('dashboard.drivers.show.medicalcard', $driver['id']),
            'alert' => $validation['errors']['medicalCard'] ?? null,
        ],
        'drugtest' => [
            'title' => 'Drug test',
            'url' => route('dashboard.drivers.show.drugtest', $driver['id']),
            'alert' => $validation['errors']['drugTest'] ?? null,
        ],
        'mvr' => [
            'title' => 'MVR',
            'url' => route('dashboard.drivers.show.mvr', $driver['id']),
            'alert' => $validation['errors']['mvr'] ?? null,
        ],
        'todo' => [
            'title' => 'To Do',
            'url' => route('dashboard.drivers.show.todo', $driver['id']),
            'alert' => $validation['valid'] ?? null,
            'classes' => 'fw-bolder text-danger',
        ],
        /*'logs' => [
            'title' => 'Logs',
            'url' => route('dashboard.drivers.show.logs', $driver['id']),
            'alert' => false,
        ],*/
    ];

    if (
        isset($driver['license']['driverType']) &&
        $driver['license']['driverType']['slug'] != 'cdl_a'
        ) {
        $licenseType = $driver['license']['driverType']['slug'];
        unset($sections['drugtest']);
    }

@endphp


<div class="d-flex flex-wrap flex-sm-nowrap">

    <div class="me-7 mb-4">
        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">

            @if( isset( $driver['profilePhoto']['showUrl'] ) )
                <img src="{{ $driver['profilePhoto']['showUrl'] }}" alt="image" />
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
                        {{ $driver['firstname'] ?? '' }} {{ $driver['lastname'] ?? '' }}
                    </a>
                    <a href="#">
                        <i class="ki-duotone ki-verify fs-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                </div>

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
                        {{ $driver['address']['city'] ?? '' }}, {{ $driver['address']['zip'] ?? '' }}
                    </a>
                    <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                        <i class="ki-duotone ki-sms fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        {{ $driver['email'] ?? '' }}
                    </a>
                </div>

            </div>

            <div class="d-flex my-4">

                @if( !$driver['isTerminated'] )

                    <span class="indicator-label">
                        <form action="{{ route('dashboard.drivers.terminate', $driver['id']) }}"
                            method="POST">
                            @csrf

                            <button class="btn btn-sm btn-light me-2" type="submit">
                                Terminate
                            </button>
                        </form>
                    </span>

                @endif

                <form id="kt_account_profile_details_form" class="form" method="POST"
                    action="{{ route('dashboard.drivers.send.oncelogin', $driver['id']) }}" 
                    enctype="multipart/form-data"
                    >
                    @csrf

                    <input type="submit" class="btn btn-sm btn-primary me-3" value="Send one-time login" />

                </form>

                <div class="me-0">
                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                    </button>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                        data-kt-menu="true">

                        <div class="menu-item px-3">

                            @if( isset( $links['drugtest_service'] ) )

                                <a href="{{ $links['drugtest_service'] }}" class="menu-link flex-stack px-3">
                                    Request Drug Test
                                    <span class="ms-2" data-bs-toggle="tooltip"
                                        aria-label=""
                                        data-bs-original-title=""
                                        data-kt-initialized="1">
                                        <i class="ki-duotone ki-information fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="d-flex flex-wrap flex-stack">
            <div class="d-flex flex-column flex-grow-1 pe-8">
                <div class="d-flex flex-wrap d-none">
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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

    </div>

</div>

<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">

    @foreach ($sections as $key => $section)
        <li class="nav-item">
            <a 
                class="nav-link text-active-primary py-5 
                {{ request()->url() == $section['url'] ? 'active' : '' }}
                {{ $section['classes'] ?? '' }}
                " 
                href="{{ $section['url'] }}"
                >
                {{ $section['title'] }}

                @if( $section['alert'] )
                    <x-alert-small class="ms-1" />
                @endif

            </a>
        </li>
    @endforeach

</ul>
