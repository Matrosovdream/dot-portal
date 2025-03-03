@extends('dashboard.layouts.app')

@section('toolbar-buttons')

@endsection

@section('content')


    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 g-4">

        @foreach($services['items'] as $service)

            <div class="col">
                <div class="card card-flush flex-row-fluid p-6 pb-5">

                    <div class="card-body text-center">

                        <a href="{{ route('dashboard.servicerequest.show', ['group' => $group['slug'], 'service' => $service['slug']]) }}">

                            <img src="assets/media/stock/food/img-2.jpg" class="rounded-3 mb-4 w-100px h-150px" alt="">

                            <div class="mb-2">
                                <span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1">
                                    {{ $service['name'] }}
                                </span>
                                <span class="text-gray-500 fw-semibold d-block fs-6 mt-n1">
                                    {{ $service['description'] }}
                                </span>
                            </div>

                        </a>

                        <span class="text-success text-end fw-bold fs-1">
                            ${{ $service['price'] ?? 'Free' }}
                        </span>

                    </div>
                </div>
            </div>

        @endforeach



    </div>


@endsection