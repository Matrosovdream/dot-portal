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
                            <span class="card-label fw-bold text-gray-900">Driver information</span>
                            <!--<span class="text-gray-500 mt-1 fw-semibold fs-6">Your credentials here</span>-->
                        </h3>

                        <div class="card-toolbar">
                        </div>

                    </div>

                    <div class="card-body pt-5">

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Driver</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    {{ $user['firstname'] ?? '-' }} {{ $user['lastname'] ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Email</div>
                            <div class="d-flex align-items-senter">
                                <span class="text-gray-900 fw-bolder fs-6">
                                    {{ $user['email'] ?? '-' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection