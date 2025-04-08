@extends('dashboard.layouts.app')

@section('toolbar-buttons')

    <a href="{{ route('dashboard.insurance-vehicles.create') }}" class="btn btn-sm fw-bold btn-primary">
        New Insurance
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
                        <th>Period</th>
                        <th class=" text-center">Added</th>
                        <th class=" text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">

                    @foreach($insurances['items'] as $insurance)

                        <tr>
                            <td>
                                <a href="{{ route('dashboard.insurance-vehicles.show', $insurance['id']) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                    data-kt-ecommerce-product-filter="product_name">
                                    {{ $insurance['name'] ?? '' }} / {{ $insurance['number'] ?? '' }}
                                    
                                    @if( !empty($insurance['file']) ) 
                                        <a href="#" class="btn btn-primary btn-sm flex-shrink-0 me-3"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_filepreview_{{ $insurance['file']['id'] }}">
                                            Preview
                                        </a>
                                        @include('dashboard.modals.layout.file-preview', [
                                            'file_id' => $insurance['file']['id'],
                                        ])
                                    @endif
                                </a>
                            </td>
                            <td>
                                {{ $insurance['start_date'] ?? '' }} : {{ $insurance['end_date'] ?? '' }}
                            </td>
                            <td class="text-center">
                                {{ $insurance['Model']->created_at->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
     
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <a href="{{ route('dashboard.insurance-vehicles.show', $insurance['id']) }}"
                                            class="menu-link px-3">Edit</a>
                                    </div>

                                    <div class="menu-item px-3">
                                        <form action="{{ route('dashboard.insurance-vehicles.destroy', $insurance['id']) }}" method="POST">
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
        </div>


        <div id="" class="row">
            {{ $insurances['Model']->links('dashboard.includes.pagination.default') }}
        </div>

    </div>

</div>

@endsection