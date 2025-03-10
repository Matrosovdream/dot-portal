<div class="card mb-5 mb-xl-10">
    <div class="card-header card-header-stretch pb-0">

        <div class="card-title">
            <h3 class="m-0">Payment Methods</h3>
        </div>

        <div class="card-toolbar m-0">

            <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">

                <li class="nav-item" role="presentation">
                    <a id="kt_billing_creditcard_tab" class="nav-link fs-5 fw-bold me-5 active" data-bs-toggle="tab"
                        role="tab" href="#kt_billing_creditcard" aria-selected="true">Credit / Debit Card</a>
                </li>

            </ul>

        </div>

    </div>

    <div id="kt_billing_payment_tab_content" class="card-body tab-content">
        <div id="kt_billing_creditcard" class="tab-pane fade show active" role="tabpanel"
            aria-labelledby="kt_billing_creditcard_tab">

            <h3 class="mb-5">My Cards</h3>

            <div class="row gx-9 gy-6">

                @foreach($user['paymentCards']['items'] as $card)

                    <div class="col-xl-6" data-kt-billing-element="card">

                        <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                            <div class="d-flex flex-column py-2">

                                <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                    {{ $card['cardholder_name'] }}
                                    <span class="badge badge-light-success fs-7 ms-2">Primary</span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <img src="assets/media/svg/card-logos/visa.svg" alt="" class="me-4">
                                    <div>
                                        <div class="fs-4 fw-bold">
                                            Visa **** 
                                            {{ substr($card['card_number'], -4) }}
                                        </div>
                                        <div class="fs-6 fw-semibold text-gray-500">
                                            Card expires at 
                                            {{ $card['expiry_date'] }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex align-items-center py-2">
                                <button class="btn btn-sm btn-light btn-active-light-primary me-3"
                                    data-kt-billing-action="card-delete">
                                    <span class="indicator-label">Delete</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_new_card">Edit</button>
                            </div>

                        </div>

                    </div>

                @endforeach


                <div class="col-xl-6">
                    <div
                        class="notice d-flex bg-light-primary rounded border-primary border border-dashed h-lg-100 p-6">

                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">

                            <div class="mb-3 mb-md-0 fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Important Note!</h4>
                                <div class="fs-6 text-gray-700 pe-7">Please carefully read
                                    <a href="#" class="fw-bold me-1">Product Terms</a>adding
                                    <br />your new payment card
                                </div>
                            </div>

                            <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap"
                                data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">
                                Add Card
                            </a>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

@include('dashboard.subscription.modals.add-new-card')