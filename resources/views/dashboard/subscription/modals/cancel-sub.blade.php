<!--begin::Modal - Credit Card Agreement-->
<div class="modal fade" id="kt_modal_cancel_sub" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h2 class="modal-title">Cancel subscription</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <!--end::Modal header-->

            <!--begin::Modal body-->
            <div class="modal-body px-10 py-10">
                <p class="fs-5 text-gray-700 mb-5">
                    By submitting your credit card information, you authorize us to store your payment method securely using our payment gateway provider, Authorize.Net. Your card will be charged for subscription services and any recurring or one-time payments associated with your account.
                </p>

                <p class="fs-5 text-gray-700 mb-5">
                    You acknowledge and agree that:
                    <ul class="fs-5 text-gray-700 ps-6 mb-5">
                        <li>Your card may be charged automatically without separate authorization for each billing cycle.</li>
                        <li>Charges will continue until you cancel your subscription or terminate your account.</li>
                        <li>We are not responsible for any bank fees, overdraft charges, or declined transactions.</li>
                        <li>You may update or remove your card at any time in your billing settings.</li>
                    </ul>
                </p>

                <p class="fs-5 text-gray-700 mb-10">
                    This agreement complies with Authorize.Net's Customer Information Manager (CIM) and recurring billing policies.
                </p>

                <form action="{{ route('dashboard.subscription.cancel') }}" method="POST">
                    @csrf
                    <input type="submit" class="btn btn-primary px-10"
                        id="kt_account_billing_cancel_subscription_btn" value="Cancel Subscription">
                </form>

            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
<!--end::Modal - Credit Card Agreement-->
