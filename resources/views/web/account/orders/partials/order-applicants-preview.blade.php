@foreach($order->travellers as $traveller)

    <div class="col active-status-block">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="mb-10">
                    {{ $traveller->name }} {{ $traveller->lastname }}
                </h5>

                @if(!$traveller->isCompletedForm())
                    <p>{{ __('We need you to complete the following questions in order to move forward with your') }} Egypt eVisa.</p>
                @endif

            </div>
            <div class="card-footer bg-white mb-10 mt-10 border-0 d-flex justify-content-between align-items-center">
                <a href="{{ route('web.account.order.applicant.personal', ['order_id' => $order->id, 'applicant_id' => $traveller->id, 'category' => 'personal']) }}"
                    class="text-decoration-none">
                    @if(!$traveller->isCompletedForm())
                        {{ __('Complete form now') }}
                    @else
                        {{ __('View details') }}
                    @endif
                </a>
                <a href="{{ route('web.account.order.applicant.personal', ['order_id' => $order->id, 'applicant_id' => $traveller->id, 'category' => 'personal']) }}"
                    class="btn-arrow">➔</a>
            </div>
        </div>
    </div>

@endforeach