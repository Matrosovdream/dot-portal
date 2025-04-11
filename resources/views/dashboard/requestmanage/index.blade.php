@extends('dashboard.layouts.app')

@section('toolbar-buttons')

    <a href="{{ route('dashboard.services.create') }}" class="btn btn-sm fw-bold btn-primary">
        New Service
    </a>

@endsection

@section('content')

    <div class="card card-flush">

        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-ecommerce-product-filter="search"
                        class="form-control form-control-solid w-250px ps-12" placeholder="Search Requests">
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-200px">Service</th>
                            <th class="min-w-200px">User</th>
                            <th class="min-w-200px text-center">Status</th>
                            <th class="min-w-200px text-center">Added</th>
                            <th class="min-w-200px text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                        @if(count($requests['items']) == 0)
                            <div class="text-center mt-10">
                                <h4>No documents found</h4>
                            </div>
                        @else

                            @foreach($requests['items'] as $request)

                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard.servicerequest.history.show', $request['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">
                                            {{ $request['service']['name'] }}
                                        </a>
                                    </td>
                                    <td class="pe-0">
                                        <span class="text-gray-800 fw-bold d-block fs-6">
                                            {{ $request['user']['firstname'] }} {{ $request['user']['lastname'] }}
                                        </span>
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
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>

                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">

                                            <div class="menu-item px-3">
                                                <a href="{{ route('dashboard.requestmanage.show', $request['id']) }}"
                                                    class="menu-link px-3">Edit</a>
                                            </div>

                                            <div class="menu-item px-3">
                                                <form action="{{ route('dashboard.requestmanage.destroy', $request['id']) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="menu-link px-3" type="submit">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            @endforeach

                        @endif

                    </tbody>
                </table>
            </div>


            <div id="" class="row">
                {{ $requests['Model']->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection