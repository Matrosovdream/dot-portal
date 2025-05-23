<div class="tab-pane active" id="kt_ecommerce_customer_payments" role="tabpanel">
    <div class="card pt-4 mb-6 mb-xl-9">

        <div class="card-header border-0">
            <div class="card-title">
                <h2>Payments history</h2>
            </div>
        </div>

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

                    <div class="text-center py-2">
                        <div class="text-gray-600 fs-2 fw-bold mb-5">No payments found</div>
                    </div>

                @endif

            </div>
        </div>

    </div>