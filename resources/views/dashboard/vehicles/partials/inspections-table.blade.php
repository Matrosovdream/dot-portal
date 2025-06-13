@if(count($inspections) > 0)

    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Inspection Number</th>
                    <th class="min-w-100px">Date</th>
                    <th class="min-w-100px">state</th>
                    <th class="min-w-100px">Inspection level</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">

                @foreach($inspections as $inspection)

                    <tr>

                        <td class="pe-0">
                            {{ $inspection['report_number'] }}
                        </td>

                        <td class="pe-0">
                            {{ dateFormat( $inspection['report_date'] ) }}
                        </td>

                        <td class="pe-0">
                            {{ $inspection['report_state'] }}
                        </td>

                        <td class="pe-0">
                            {{ $inspection['inspection_level'] }}
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


@php /*
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
*/ @endphp