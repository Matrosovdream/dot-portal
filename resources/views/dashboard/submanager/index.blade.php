@extends('dashboard.layouts.app')

@section('toolbar-buttons')

    <a href="{{ route('dashboard.usersubscriptions.create') }}" class="btn btn-sm fw-bold btn-primary">
        New Subscription
    </a>

@endsection 

@section('content')

<div class="card card-flush">

    <form action="{{ route('dashboard.usersubscriptions.index') }}" method="GET">

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
                        placeholder="Find subscriptions">
                </div>

                <button type="submit" class="btn btn-primary">Filter</button>

            </div>
        </div>

    </form>

    <div class="card-body pt-0">
        <div class="table-responsive">

        @if(count($subs['items']) == 0)
            <div class="text-center mt-10">
                <h4>No subscriptions found</h4>
            </div>
        @else

            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="">User</th>
                        <th>Subscription</th>
                        <th class="text-center">Drivers / Price Per Driver / Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Start date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">

                    @foreach($subs['items'] as $item)

                        <tr>
                            <td>
                                <a href="{{ route('dashboard.usersubscriptions.show', $item['id']) }}"
                                    class="text-gray-800 text-hover-primary fs-5 fw-bold">
                                    {{ $item['user']['firstname'] }} {{ $item['user']['lastname'] }} 
                                    <br/>
                                    <span class="text-muted fs-7">
                                        {{ $item['user']['email'] }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                {{ $item['subscription']['name'] ?? '' }}
                            </td>
                            <td class="text-center">
                                @if( $item['subscription'] )
                                    {{ $item['drivers_number'] }} / ${{ $item['price_per_driver'] }} / ${{ $item['price'] }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if(  $item['status'] == 'active' )
                                    <span class="badge badge-light-success">Active</span>
                                @elseif( $item['status'] == 'disabled' )
                                    <span class="badge badge-light-danger">Inactive</span>
                                @elseif( $item['status'] == 'expired' )
                                    <span class="badge badge-light-warning">Expired</span>
                                @else
                                    <span class="badge badge-light-info">Unknown</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ dateFormat( $item['start_date'] ) }}
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
     
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <a href="{{ route('dashboard.usersubscriptions.show', $item['id']) }}"
                                            class="menu-link px-3">Edit</a>
                                    </div>

                                    <div class="menu-item px-3">
                                        <form action="{{ route('dashboard.usersubscriptions.destroy', $item['id']) }}" method="POST">
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
            {{ $subs['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
        </div>

    </div>

</div>

@endsection