
<div class="card">

    <div class="card-header card-header-stretch border-bottom border-gray-200">

        <div class="card-title">
            <h3 class="fw-bold m-0">Billing History</h3>
        </div>

        <div class="card-toolbar m-0">

            <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">

                <li class="nav-item" role="presentation">
                    <a id="kt_billing_alltime_tab" class="nav-link fs-5 fw-semibold active" data-bs-toggle="tab" role="tab"
                        href="#kt_billing_all" aria-selected="false" tabindex="-1">All Time</a>
                </li>

            </ul>

        </div>
    </div>

    <div class="tab-content">

        <div id="kt_billing_all" class="card-body p-0 tab-pane fade active show" role="tabpanel"
            aria-labelledby="kt_billing_all">
            <div class="table-responsive">

                @if(isset($paymentHistory))

                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                            <tr>
                                <td>Date</td>
                                <td>Status</td>
                                <td>Notes</td>
                                <td>Amount</td>
                                <td>Transaction ID</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">

                        @php
                        //dd($paymentHistory['items']);
                        @endphp

                            @foreach($paymentHistory['items'] as $payment)

                                <tr>
                                    <td>{{ $payment['payment_date'] }}</td>
                                    <td>
                                        <span class="badge badge-light fw-bold">{{ $payment['status'] }}</span>
                                    </td>
                                    <td>
                                        {{ $payment['notes'] ?? '' }}
                                    </td>
                                    <td>
                                        ${{ $payment['amount'] }}
                                    </td>
                                    <td>
                                        {{ $payment['transaction_id'] }}
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>
                    </table>

                @else

                    <div class="text-center py-10">
                        <div class="text-gray-600 fs-3 fw-bold mb-5">No payments found</div>
                    </div>

                @endif

            </div>
        </div>

    </div>
</div>