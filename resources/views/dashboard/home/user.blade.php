@extends('dashboard.layouts.app')

@section('toolbar-buttons')



@endsection

@section('content')

    @if( isset($company['banner_new_company']) )

        <div class="alert alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h4 class="mb-2 text-dark">
                    Welcome!
                </h4>
                <span class="text-gray-700">
                    To finish setup, please complete your Driver Info and Truck Info.
                </span>
            </div>

            <div class="ms-sm-auto mt-4 mt-sm-0">

                <a href="{{ route('dashboard.drivers.index') }}" class="btn btn-primary px-6 flex-shrink-0 align-self-center me-6">
                    <i class="ki-duotone ki-user fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Add Drivers
                </a>

                <a href="{{ route('dashboard.vehicles.index') }}" class="btn btn-primary px-6 flex-shrink-0 align-self-center">
                    <i class="ki-duotone ki-car fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Add Trucks
                </a>

            </div>
        </div>

    @endif


    <div class="row gx-5 gx-xl-10 mb-xl-10">

        <div class="row gx-5 gx-xl-10">

            <div class="col-sm-6 mb-5 mb-xl-10">

                @include('dashboard.home.sections.company.company-info')

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

        <div class="row gx-5 gx-xl-10">

            <div class="col-xl-6 mb-5 mb-xl-10">

                @include('dashboard.home.sections.company.latest-inspections', [
                    'data' => $saferwebLatest['inspections'] ?? [],
                ])

            </div>    

            <div class="col-xl-6 mb-5 mb-xl-10">

                @include('dashboard.home.sections.company.latest-crashes', [
                    'data' => $saferwebLatest['crashes'] ?? [],
                ])    

            </div>

        </div>


    </div>




@endsection