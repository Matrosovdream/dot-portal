@extends('dashboard.layouts.app')

@section('toolbar-buttons')

    <a href="{{ route('dashboard.users.create') }}" class="btn btn-sm fw-bold btn-primary">
        New User
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
                        class="form-control form-control-solid w-250px ps-12" placeholder="Search Users">
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">

            @if(count($users) == 0)
                <div class="text-center mt-10">
                    <h4>No users found</h4>
                </div>
            @else

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-200px">Name</th>
                            <th class="min-w-200px">Email</th>
                            <th class="min-w-200px text-center">Role</th>
                            <th class="min-w-200px text-center">Added</th>
                            <th class="min-w-200px text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                            @foreach($users as $user)

                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard.users.show', $user->id) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">
                                            {{ $user->firstname }} {{ $user->lastname }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-light-{{ $user->role == 'admin' ? 'primary' : 'success' }} badge-pill">
                                            {{ $user->getRole()->title ?? '' }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-0">
                                        {{ dateFormat( $user->created_at ) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>

                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">

                                            <div class="menu-item px-3">
                                                <a href="{{ route('dashboard.users.show', $user->id) }}"
                                                    class="menu-link px-3">Edit</a>
                                            </div>

                                            <div class="menu-item px-3">
                                                <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST">
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
                {{ $users->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection