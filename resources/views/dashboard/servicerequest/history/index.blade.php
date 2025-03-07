@extends('dashboard.layouts.app')

@section('toolbar-buttons')
@endsection

@section('content')

    <div class="card card-flush">

        <div class="card-body pt-0 mt-5">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_ecommerce_products_table .form-check-input" value="1" />
                                </div>
                            </th>
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
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
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
                                    <a href="{{ route('dashboard.servicerequest.history.show', $request['id']) }}"
                                        class="menu-link px-3">Show
                                    </a>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>


            <div id="" class="row">
                {{ $requests['Model']->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection