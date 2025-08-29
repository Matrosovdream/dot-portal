@extends('dashboard.layouts.app')

@section('toolbar-buttons')

@endsection

@section('content')

    <div class="row">

        @if(count($services['items']) == 0)
            <div class="text-center mt-10">
                <h4>No requests found</h4>
            </div>
        @else

            @foreach($services['items'] as $service)

                <div class="col col-md-4 mb-4">
                    <div class="card card-flush flex-row-fluid p-0 pb-0 mh-400px">

                        <div class="card-body text-center">

                            <a
                                href="{{ $service['url'] }}">

                                <img src="https://www.lifestyleglitz.com/wp-content/uploads/2019/07/Follow-These-Tips-To-File-Your-Personal-Injury-Claim.png" class="rounded-3 mb-4 w-100" alt="">

                                <div class="mb-2">
                                    <span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">
                                        {{ $service['name'] }}
                                    </span>
                                    <span class="text-gray-500 fw-semibold d-block fs-6 mt-n1">

                                    </span>
                                </div>

                            </a>

                            <span class="text-success text-end fw-bold fs-1">
                                @if($service['is_paid'])
                                    ${{ $service['price'] ?? 0.00 }}
                                    @if($service['price_title'])
                                        - {{ $service['price_title'] }}
                                    @endif
                                @else
                                    <span class="text-success">Free</span>
                                @endif
                                
                            </span>

                        </div>
                    </div>
                </div>

            @endforeach

        @endif



    </div>


@endsection