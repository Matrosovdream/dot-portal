<div class="card mb-5 mb-xl-10">

    <div class="card-body">

        <!--
        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-12 p-6">

            <i class="ki-duotone ki-information fs-2tx text-warning me-4">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>

            <div class="d-flex flex-stack flex-grow-1">

                <div class="fw-semibold">
                    <h4 class="text-gray-900 fw-bold">We need your attention!</h4>
                    <div class="fs-6 text-gray-700">Your payment was declined. To start using tools, please
                        <a href="#" class="fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Add
                            Payment Method</a>.
                    </div>
                </div>

            </div>

        </div>
        -->

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
                        @if( $subscription['subscription']['duration'] == 'monthly' )
                            Per Month
                        @elseif( $subscription['subscription']['duration'] == 'yearly' )
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
                    <div 
                        class="progress-bar bg-primary" 
                        role="progressbar" 
                        style="width: {{ $subscription['driversUsedPercent']  }}%" 
                        ria-valuenow="{{ $subscription['driversUsedPercent']  }}"
                        aria-valuemin="0" 
                        aria-valuemax="100"
                        ></div>
                </div>

                <div class="fs-6 text-gray-600 fw-semibold mb-10">
                    {{ $subscription['driversRemained'] }} Users remaining until your plan requires update
                </div>

                <div class="d-flex justify-content-end pb-0 px-0">

                    <form action="{{ route('dashboard.subscription.cancel') }}" method="POST">
                        @csrf
                    
                        <input type="submit" class="btn btn-light btn-active-light-primary me-2"
                            id="kt_account_billing_cancel_subscription_btn" value="Cancel Subscription">
                    </form>


                    <!--
                    <a href="#" class="btn btn-light btn-active-light-primary me-2"
                        id="kt_account_billing_cancel_subscription_btn">
                        Cancel Subscription
                    </a>
                    -->
                    <button class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_upgrade_plan">Upgrade Plan</button>
                </div>

            </div>

        </div>
    </div>
</div>