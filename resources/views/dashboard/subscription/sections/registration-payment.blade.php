<div class="card mb-5 mb-xl-10">
    <div class="card-body">

        <div class="row">
            <div class="col-lg-7">
                <h3 class="mb-2 text-gray-900 fw-bold">
                    Initial Registration Fee
                </h3>
                <p class="fs-6 text-gray-600 fw-semibold mb-6 mb-lg-15">
                    One-time setup fee for account activation and onboarding
                </p>

                <div class="fs-5 mb-2">
                    <span class="text-gray-800 fw-bold me-1">
                        $199
                    </span>
                    <span class="text-gray-600 fw-semibold">
                        One-Time Fee
                    </span>
                </div>

                <div class="fs-6 text-gray-600 fw-semibold">
                    This charge only occurs once during initial setup
                </div>
            </div>

            <div class="col-lg-5 d-flex align-items-end justify-content-end">
                @if($user['paymentCards']['items']->count() > 0)
                    <form action="#" method="POST">
                        @csrf
                        <button class="btn btn-success">
                            Pay Registration Fee
                        </button>
                    </form>
                @else
                    <div class="d-flex flex-column">
                        <span class="text-gray-800 fw-bold fs-5 mb-2">Add a payment method to upgrade</span>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>