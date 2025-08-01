@extends('dashboard.layouts.app')

@section('toolbar-buttons')

    <a href="{{ route('dashboard.subplans.create') }}" class="btn btn-sm fw-bold btn-primary">
        <i class="fa fa-plus"></i>
        New Plan
    </a>

@endsection

@section('content')

    <div class="card card-flush">

        <div class="card-body pt-0">
            <div class="table-responsive">

            @if(count($plans['items']) == 0)
                <div class="text-center mt-10">
                    <h4>No plans found</h4>
                </div>
            @else

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="">Name</th>
                            <th class="text-center">Price per driver</th>
                            <th class="text-center">Driver's amount</th>
                            <th class="">Duration</th>
                            <th class="">Description</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                            @foreach($plans['items'] as $item)

                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard.subplans.show', $item['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">
                                            {{ $item['name'] }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        ${{ $item['price_per_driver'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ implode('-', [$item['drivers_amount_from'], $item['drivers_amount_to']]) }}
                                    </td> 
                                    <td>
                                        {{ $item['duration'] ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $item['short_description'] }}
                                    </td>
                                    <td class="text-end">
                                        <a 
                                            href="{{ route('dashboard.subplans.show', $item['id']) }}" 
                                            class="btn btn-sm btn-light-primary">
                                            <i class="fa fa-eye"></i> 
                                            View
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                    </tbody>
                </table>

            @endif

            </div>


            <div id="" class="row">
                @if( isset($plans['Model']) )
                    {{ $plans['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
                @endif
            </div>

        </div>

    </div>

@endsection