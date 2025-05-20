<div class="card mb-5 mb-xl-10">
    <div class="card-body">

        @if($subscription['subscription'])
            <div class="row">
                <div class="col-lg-7">
                    <h3 class="mb-2">
                        Active until
                        {{ date('M d, Y', strtotime($subscription['end_date'])) }}
                    </h3>
                    <p class="fs-6 text-gray-600 fw-semibold mb-6 mb-lg-15">
                        We will send you a notification upon Subscription expiration
                    </p>

                    <div class="fs-5 mb-2">
                        <span class="text-gray-800 fw-bold me-1">
                            ${{ $subscription['subscription']['price'] }}
                        </span>
                        <span class="text-gray-600 fw-semibold">
                            @if($subscription['subscription']['duration'] == 'monthly')
                                Per Month
                            @elseif($subscription['subscription']['duration'] == 'yearly')
                                Per Year
                            @endif
                        </span>
                    </div>

                    <div class="fs-6 text-gray-600 fw-semibold">
                        {{ $subscription['subscription']['name'] }} plan
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="d-flex text-muted fw-bold fs-5 mb-3">
                        <span class="flex-grow-1 text-gray-800">Drivers</span>
                        <span class="text-gray-800">
                            {{ $subscription['driversUsed'] }} of {{ $subscription['subscription']['drivers_amount'] }} Used
                        </span>
                    </div>

                    <div class="progress h-8px bg-light-primary mb-2">
                        <div class="progress-bar bg-primary" role="progressbar"
                            style="width: {{ $subscription['driversUsedPercent']  }}%"
                            aria-valuenow="{{ $subscription['driversUsedPercent']  }}" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>

                    <div class="fs-6 text-gray-600 fw-semibold mb-10">
                        {{ $subscription['driversRemained'] }} Users remaining until your plan requires update
                    </div>

                    <div class="d-flex justify-content-end pb-0 px-0">

                        <a href="#" class="btn btn-light btn-active-light-primary me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_cancel_sub">
                            Cancel Subscription
                        </a>

                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_upgrade_plan">Upgrade Plan</button>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-lg-7">
                    <h3 class="mb-2 text-gray-900 fw-bold">No Active Subscription</h3>
                    <p class="fs-6 text-gray-600 fw-semibold mb-6 mb-lg-15">
                        You currently don’t have an active subscription. Please upgrade to access all features.
                    </p>
                </div>

                <div class="col-lg-5 d-flex align-items-end justify-content-end">
                    @if($user['paymentCards']['items']->count() > 0)
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">
                            Upgrade Plan
                        </button>
                    @else
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold fs-5 mb-2">Add a payment method to upgrade</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>

@if($subscription['subscription'])
    @include('dashboard.subscription.modals.cancel-sub')
@endif