<div class="card card-flush h-xl-100">

    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Latest inspections</span>
            <span class="text-gray-500 pt-1 fw-semibold fs-6">comment here</span>
        </h3>

        <div class="card-toolbar">
            <a href="{{ route('dashboard.saferweb.inspections.index') }}" class="btn btn-sm btn-light">
                View all
            </a>
        </div>
    </div>

    <div class="card-body py-3">
        <div class="table-responsive">

            <table class="table table-row-dashed align-middle gs-0 gy-4">

                <thead>
                    <tr class="fs-7 fw-bold border-0 text-gray-500">
                        <!--<th class="min-w-150px" colspan="2">Unit Vin</th>-->
                        <th class="min-w-150px" colspan="2">Report Number</th>
                        <th class="min-w-150px" colspan="2">Report date</th>
                        <th class="min-w-150px" colspan="2"></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach( $data['items'] as $item )

                        <tr>
                            <!--
                            <td class="" colspan="2">
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                    {{ $item['unit_vin'] }}
                                </a>
                            </td>
                            -->
                            <td class="pe-0" colspan="2">
                                <span class="text-gray-800 fw-bold fs-6 me-1">
                                    {{ $item['report_number'] }}
                                </span>
                            </td>
                            <td class="" colspan="2">
                                <span class="text-gray-900 fw-bold fs-6 me-3">
                                    {{ dateFormat( $item['report_date'] ) }}
                                </span>
                            </td>
                            <td class="text-end" colspan="2">
                                <a href="{{ route('dashboard.saferweb.inspections.show', $item['id']) }}" class="btn btn-sm btn-light-primary">
                                    View
                                </a>
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>