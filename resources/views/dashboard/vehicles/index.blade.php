@extends('dashboard.layouts.app')

@section('toolbar-buttons')

    <a href="{{ route('dashboard.vehicles.create') }}" class="btn btn-sm fw-bold btn-primary">
        New vehicle
    </a>

@endsection

@section('content')

    <div class="card card-flush">

        <form action="{{ route('dashboard.vehicles.index') }}" method="GET">

            <div class="card-header align-items-center py-5 gap-2 gap-md-5">

                <div class="card-title"></div>

                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="q" value="{{ request()->q ?? '' }}"
                            data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="Find vehicles">
                    </div>

                    <button type="submit" class="btn btn-primary">Filter</button>

                </div>
            </div>

        </form>

        <div class="card-body pt-0">
            <div class="table-responsive">

                @if(count($vehicles['items']) == 0)

                    <div class="text-center mt-10">
                        <h4>No vehicles found</h4>
                    </div>

                @else

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="">Number</th>
                                <th class="">Vin</th>
                                <th class="">Driver</th>
                                <th class="">Unit Type</th>
                                <th class="">Ownership Type</th>
                                <th class="">Reg Expire date</th>
                                <th class="">inspect Expire date</th>
                                <th class="min-w-200px text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">

                            @foreach($vehicles['items'] as $vehicle)

                                <tr>
                                    <td>
                                        <span class="d-inline-flex align-items-center">
                                            <a href="{{ route('dashboard.vehicles.show', $vehicle['id']) }}"
                                                class="text-gray-800 text-hover-primary fs-5 fw-bold me-1">
                                                {{ $vehicle['number'] }}
                                            </a>
                                            @if( 
                                                isset($validation[ $vehicle['id'] ]) && 
                                                !$validation[ $vehicle['id'] ]['valid'] 
                                                )    
                                                <i class="ki-duotone ki-information fs-2x text-warning">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        {{ $vehicle['vin'] }}
                                    </td>
                                    <td>
                                        {{ $vehicle['driver']['firstname'] }} {{ $vehicle['driver']['lastname'] }}
                                    </td>
                                    <td>
                                        {{ $vehicle['unitType']['name'] }}
                                    </td>
                                    <td>
                                        {{ $vehicle['ownershipType']['name'] }}
                                    </td>
                                    <td>
                                        @if( $vehicle['regExpireDate'] )
                                            {{ dateFormat( $vehicle['regExpireDate'] ) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if( $vehicle['inspectionExpireDate'] )
                                            {{ dateFormat( $vehicle['inspectionExpireDate'] ) }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>

                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">

                                            <div class="menu-item px-3">
                                                <a href="{{ route('dashboard.vehicles.show', $vehicle['id']) }}"
                                                    class="menu-link px-3">Edit</a>
                                            </div>

                                            <div class="menu-item px-3">
                                                <form action="{{ route('dashboard.vehicles.destroy', $vehicle['id']) }}"
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

                        </tbody>
                    </table>

                @endif

            </div>


            <div id="" class="row">
                {{ $vehicles['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection