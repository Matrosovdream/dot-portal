@php

$sections = [
    'overview' => [
        'title' => 'Overview',
        'url' => route('dashboard.usersubscriptions.show', $sub['id']),
    ],
    'general' => [
        'title' => 'Subscription info',
        'url' => route('dashboard.usersubscriptions.show.profile', $sub['id']),
    ],
    'user' => [
        'title' => 'User info',
        'url' => route('dashboard.usersubscriptions.show.user', $sub['id']),
    ],
    'company' => [
        'title' => 'Company info',
        'url' => route('dashboard.usersubscriptions.show.company', $sub['id']),
    ],
];

@endphp


<div class="d-flex flex-wrap flex-sm-nowrap">

    <div class="flex-grow-1">

        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
            <div class="d-flex flex-column">

                <div class="d-flex align-items-center mb-2">
                    <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                        {{ $sub['user']['firstname'] }} {{ $sub['user']['lastname'] }} 
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
                    {{ $sub['subscription']['name'] ?? '' }}
                    </a>

                </div>

            </div>

            <div class="d-flex my-4">

                @if( $sub['status'] == 'disabled' )

                    <form id="kt_account_profile_details_form" class="form" method="POST"
                        action="{{ route('dashboard.usersubscriptions.send.paymentlink', $sub['id']) }}" 
                        enctype="multipart/form-data"
                        >
                        @csrf

                        <input type="submit" class="btn btn-sm btn-primary me-3" value="Send payment link" />

                    </form>

                @endif
                
                <form id="kt_account_profile_details_form" class="form" method="POST"
                    action="{{ route('dashboard.usersubscriptions.send.oncelogin', $sub['id']) }}" 
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
                " 
                href="{{ $section['url'] }}"
                >
                {{ $section['title'] }}
            </a>
        </li>
    @endforeach

</ul>