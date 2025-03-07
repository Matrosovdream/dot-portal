<div class="tab-pane fade active" id="kt_ecommerce_customer_history" role="tabpanel">
    <div class="card pt-4 mb-6 mb-xl-9">

        <div class="card-header border-0">
            <div class="card-title">
                <h2>History</h2>
            </div>
        </div>

        <div class="card-body pt-0 pb-5">

            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-100px">User</th>
                            <th class="min-w-100px">Comment</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                        @foreach($request['history']['items'] as $history)

                            <tr>

                                <td class="pe-0">
                                    {{ $history['status']['name'] ?? '' }}
                                </td>

                                <td class="pe-0"> 
                                    {{ $history['user']['firstname'] ?? '' }} {{ $history['user']['lastname'] ?? '' }} 
                                </td>

                                <td class="pe-0">
                                    {{ $history['comment'] ?? '' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>