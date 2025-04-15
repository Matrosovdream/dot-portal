@if(count($inspections) > 0)

    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Inspection Number</th>
                    <th class="min-w-100px">Date</th>
                    <th class="min-w-100px">Document</th>
                    <th class="min-w-100px text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">

                @foreach($inspections as $inspection)

                    <tr>

                        <td class="pe-0">
                            {{ $inspection['inspection_number'] }}
                        </td>

                        <td class="pe-0">
                            {{ $inspection['inspection_date'] }}
                        </td>

                        <td class="pe-0">

                        </td>

                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_product_form_field_{{ $inspection['id'] }}">
                                        Edit
                                    </a>

                                </div>

                                <div class="menu-item px-3">

                                    <form
                                        action="{{ route('dashboard.vehicles.show.inspections.destroy', ['vehicle_id' => $vehicle['id'], 'inspection_id' => $inspection['id']]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="menu-link px-3">
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

@else

    <div class="text-center mt-10">
        <h4>No inspections found</h4>
    </div>

@endif

<!-- Create inspection modal -->
@include(
    'dashboard.vehicles.modals.create-inspection',
    [
        'vehicle' => $vehicle,
    ]
)

<!-- Update inspection modal -->
@foreach($inspections as $item)
    @include(
        'dashboard.vehicles.modals.edit-inspection',
        [
            'vehicle' => $vehicle,
            'inspection' => $item
        ]
    )
@endforeach
