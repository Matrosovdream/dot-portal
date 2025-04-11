@extends('dashboard.layouts.app')

@section('toolbar-buttons')

    <a href="{{ route('dashboard.drivers.create') }}" class="btn btn-sm fw-bold btn-primary">
        New Driver
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
                        class="form-control form-control-solid w-250px ps-12" placeholder="Search Drivers">
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="">Driver Name</th>
                            <th class=" text-center">Driver Type</th>
                            <th class=" text-center">Dob</th>
                            <th class=" text-center">License type</th>
                            <th class=" text-center">License state / Number</th>
                            <th class=" text-center">Hire date</th>
                            <th class=" text-center">Added</th>
                            <th class=" text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                        @if(count($$drivers['items']) == 0)
                            <div class="text-center mt-10">
                                <h4>No documents found</h4>
                            </div>
                        @else

                            @foreach($drivers['items'] as $driver)

                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard.drivers.show', $driver['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">
                                            {{ $driver['user']['firstname'] ?? '' }}
                                            {{ $driver['user']['lastname'] ?? '' }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ $driver['Model']->driverType->title ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $driver['dob'] ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $driver['license']['Model']['type']->title ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $driver['license']['Model']->countryState->name ?? '' }} /
                                        {{ $driver['license']['license_number'] ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $driver['hire_date'] ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $driver['Model']->created_at->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>

                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">

                                            <div class="menu-item px-3">
                                                <a href="{{ route('dashboard.drivers.show', $driver['id']) }}"
                                                    class="menu-link px-3">Edit</a>
                                            </div>

                                            <div class="menu-item px-3">
                                                <form action="{{ route('dashboard.drivers.destroy', $driver['id']) }}"
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
                {{ $drivers['Model']->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection