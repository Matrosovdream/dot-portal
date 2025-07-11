@extends('dashboard.layouts.app')

@section('content')

    <div class="card">
        <div class="card-body p-lg-17">

            <div class="position-relative mb-17">

                <div class="overlay overlay-show">

                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px"
                        style="background-image:url('https://i.ytimg.com/vi/6km8SBLOcps/maxresdefault.jpg')">
                    </div>

                    <div class="overlay-layer rounded bg-black" style="opacity: 0.4"></div>
                </div>

                <div class="position-absolute text-white mb-8 ms-10 bottom-0">
                    <h3 class="text-white fs-2qx fw-bold mb-3 m">
                        {{ $service['name'] }}
                    </h3>
                    <div class="fs-5 fw-semibold">
                        Some text here
                    </div>
                </div>

            </div>

            <div class="d-flex flex-column flex-lg-row mb-17">

                <div class="flex-lg-row-fluid me-0 me-lg-10">

                    @if( $formPath )
                        @include('dashboard.servicerequest.sections.submit-form-predefined', ['formPath' => $formPath])
                    @else
                        @include('dashboard.servicerequest.sections.submit-form')
                    @endif
                    
                </div>

                <div class="flex-lg-row-auto w-100 w-lg-275px w-xxl-330px">
                    <div class="card bg-light">

                        <div class="card-body">

                            <div class="mb-7">

                                @if( $service['slug'] == 'msc-150-update' )

                                    @php
                                    $saferweb = $company['saferweb'] ?? null;
                                    $apiData = $saferweb['api_data'] ?? null;

                                    $list = [
                                        'entity_type' => [
                                            'label' => 'Entity type',
                                            'value' => $saferweb['entity_type'] ?? null
                                        ],
                                        'legal_name' => [
                                            'label' => 'Legal Name',
                                            'value' => $saferweb['legal_name'] ?? null
                                        ],
                                        'physical_address' => [
                                            'label' => 'Physical Address',
                                            'value' => $saferweb['physical_address'] ?? null
                                        ],
                                        'mailing_address' => [
                                            'label' => 'Mailing Address',
                                            'value' => $saferweb['mailing_address'] ?? null     
                                        ],
                                        'power_units' => [
                                            'label' => 'Power Units',
                                            'value' => $apiData['truck_units'] ?? null
                                        ],
                                        'drivers_number' => [
                                            'label' => 'Drivers Number',
                                            'value' => $apiData['total_drivers'] ?? null
                                        ],
                                        'msc150_update' => [
                                            'label' => 'Last MCS‑150 date',
                                            'value' => $saferweb['latest_update'] ?? null
                                        ],
                                    ];
                                    @endphp

                                    <h2 class="fs-1 text-gray-800 w-bolder mb-6">
                                        Company details
                                    </h2>

                                    <div class="my-2">

                                        @foreach( $list as $key => $data )

                                            @if( $data['value'] )
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="text-gray-600 fw-semibold fs-6">
                                                        {{ $data['label'] }} - 
                                                        <b>{{ $data['value'] }}</b>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                        @endforeach
    
                                    </div>

                                @else

                                    <h2 class="fs-1 text-gray-800 w-bolder mb-6">
                                        {{ $service['name'] }} details
                                    </h2>

                                    <p class="fw-semibold fs-6 text-gray-600">
                                        {{ $service['description'] }}
                                    </p>

                                @endif

                            </div>

                            <!--
                            <div class="mb-8">

                                <h4 class="text-gray-700 w-bolder mb-0">Title</h4>
                                <div class="my-2">

                                    <div class="d-flex align-items-center mb-3">
                                        <span class="bullet me-3"></span>
                                        <div class="text-gray-600 fw-semibold fs-6">Lorem ipsum</div>
                                    </div>

                                </div>

                            </div>
                            <a href="pages/blog/post.html" class="link-primary fs-6 fw-semibold">Explore More</a>
                            -->

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection