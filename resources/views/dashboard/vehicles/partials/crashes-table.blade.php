@if(count($crashes) > 0)

    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Inspection Number</th>
                    <th class="min-w-100px">Date</th>
                    <th class="min-w-100px">state</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">

                @foreach($crashes as $item)

                    <tr>

                        <td class="pe-0">
                            {{ $item['report_number'] }}
                        </td>

                        <td class="pe-0">
                            {{ dateFormat( $item['report_date'] ) }}
                        </td>

                        <td class="pe-0">
                            {{ $item['report_state'] }}
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
