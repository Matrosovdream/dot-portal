@extends('dashboard.layouts.app')

@section('toolbar-buttons')



@endsection

@section('content')

    <div class="row gx-5 gx-xl-10 mb-xl-10">

        <div class="row gx-5 gx-xl-10">

            <div class="col-sm-6 mb-5 mb-xl-10">

                <div class="card card-flush h-lg-100">

                    <div class="card-header pt-5">

                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Company information</span>
                            <!--<span class="text-gray-500 mt-1 fw-semibold fs-6">Your credentials here</span>-->
                        </h3>

                        <div class="card-toolbar">
                        </div>

                    </div>

                    <div class="card-body pt-5">

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">USDOT</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    #{{ $user['company']['dot_number'] ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">MC Number</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    #{{ $user['company']['mc_number'] ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Last MCS-150 date</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    @if( $company['saferweb']['latest_update'] )
                                        {{ dateFormat( $company['saferweb']['latest_update'] ) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">ISS Score</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    -
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>


                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">
                                <a href="{{ route('dashboard.profile.company.edit') }}" class="text-primary opacity-75-hover fs-6 fw-semibold">
                                    Update credentials
                                </a>
                            </div>
                            <div class="d-flex align-items-senter">

                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">

                    <div class="card-body d-flex justify-content-between align-items-start flex-column">

                        <div class="m-0">
                            <img src="assets/media/svg/brand-logos/instagram-2-1.svg" class="w-35px" alt="">
                        </div>

                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">
                                {{ $stats['drivers']['total'] ?? 0 }}
                            </span>
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-500">
                                    Drivers
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('dashboard.drivers.index') }}" class="btn btn-sm btn-light-primary">
                            View All
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">

                    <div class="card-body d-flex justify-content-between align-items-start flex-column">

                        <div class="m-0">
                            <img src="assets/media/svg/brand-logos/instagram-2-1.svg" class="w-35px" alt="">
                        </div>

                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">
                                {{ $stats['vehicles']['total'] ?? 0 }}
                            </span>
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-500">
                                    Vehicles
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('dashboard.vehicles.index') }}" class="btn btn-sm btn-light-primary">
                            View All
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">

                    <div class="card-body d-flex justify-content-between align-items-start flex-column">

                        <div class="m-0">
                            <img src="assets/media/svg/brand-logos/instagram-2-1.svg" class="w-35px" alt="">
                        </div>

                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">
                                {{ $stats['insurances']['total'] ?? 0 }}
                            </span>
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-500">
                                    Insurances
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('dashboard.insurance-vehicles.index') }}" class="btn btn-sm btn-light-primary">
                            View All
                        </a>
                    </div>

                </div>
            </div>

        </div>

        <div class="row gx-5 gx-xl-10">

            <div class="col-sm-6 mb-5 mb-xl-10">

                <div class="card card-flush h-lg-100">

                    <div class="card-header pt-5">

                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Daily Updates</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">
                                Last updated on 
                                {{ $stats['inspections']['last_update'] ?? date('Y-m-d') }}
                            </span>
                        </h3>

                        <div class="card-toolbar">
                        </div>

                    </div>

                    <div class="card-body pt-5">

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Inspections</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    {{ $stats['inspections']['day'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Crashes</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    {{ $stats['crashes']['day'] ?? 0 }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-sm-6 mb-5 mb-xl-10">

                <div class="card card-flush h-lg-100">

                    <div class="card-header pt-5">

                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Monthly Updates</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">
                                Last updated on 
                                {{ $stats['inspections']['last_update'] ?? date('Y-m-d') }}
                            </span>
                        </h3>

                        <div class="card-toolbar">
                        </div>

                    </div>

                    <div class="card-body pt-5">

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Inspections</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    {{ $stats['inspections']['month'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Crashes</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    {{ $stats['crashes']['month'] ?? 0 }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>



    </div>




@endsection