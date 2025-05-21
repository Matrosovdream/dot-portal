@extends('dashboard.layouts.app')

@section('toolbar-buttons')
@endsection

@section('content')

    <div class="card card-flush">

        <div class="card-body pt-0 mt-5">
            <div class="table-responsive">

                @if(count($requests['items']) == 0)
                    <div class="text-center mt-10">
                        <h4>No requests found</h4>
                    </div>
                @else

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-200px">Service</th>
                            <th class="min-w-200px text-center">Status</th>
                            <th class="min-w-200px text-center">Added</th>
                            <th class="min-w-200px text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                        @foreach($requests['items'] as $request)

                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.servicerequest.history.show', $request['id']) }}"
                                        class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                        data-kt-ecommerce-product-filter="product_name">
                                        {{ $request['service']['name'] }}
                                    </a>
                                </td>
                                <td class="text-center pe-0">
                                    <span class="text-gray-800 fw-bold d-block fs-6">
                                        {{ $request['status']['name'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-gray-800 fw-bold d-block fs-6">
                                        {{ $request['Model']->created_at->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="text-center">

                                    @if( $request['status']['id'] == 2 && !$request['is_paid'] )
                                        <a href="{{ route('dashboard.servicerequest.history.showpay', $request['id']) }}"
                                            class="menu-link px-3">Pay</a>
                                    @else
                                        <a href="{{ route('dashboard.servicerequest.history.show', $request['id']) }}"
                                            class="menu-link px-3">Show
                                        </a>
                                    @endif
                                </td>
                            </tr>

                        @endforeach

                    </tbody>
                </table>

                @endif

            </div>


            <div id="" class="row">
                {{ $requests['Model']->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection