<!--begin::Modal - Cancel Subscription-->
<div class="modal fade" id="kt_modal_cancel_sub" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded">
            
            <!--begin::Modal header-->
            <div class="modal-header">
                <h2 class="modal-title">Cancel Subscription</h2>
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

                <!-- Refund Summary -->
                <div class="mb-7">
                    <h4 class="fw-bold mb-2">Refund Summary</h4>
                    <p class="fs-5 text-gray-700">
                        You are about to cancel your subscription. Upon confirmation, a refund of <strong>${{ $subscription['refundSum'] }}</strong> will be issued to your original payment method.
                    </p>
                </div>

                <!-- Cancellation Terms -->
                <div class="mb-7">
                    <h4 class="fw-bold mb-2">Cancellation Terms</h4>
                    <ul class="fs-5 text-gray-700 ps-6 mb-0">
                        <li>Your subscription will be canceled immediately upon confirmation.</li>
                        <li>Any remaining unused days will be refunded based on your billing plan.</li>
                        <li>Your card may have been charged automatically as part of recurring billing.</li>
                        <li>We are not responsible for any bank or overdraft fees resulting from the cancellation.</li>
                        <li>This policy complies with Authorize.Net's CIM and subscription billing standards.</li>
                    </ul>
                </div>

                <!-- Cancel Button -->
                <form action="{{ route('dashboard.subscription.cancel') }}" method="POST" class="text-center">
                    @csrf
                    <input type="submit" class="btn btn-danger px-10" id="kt_account_billing_cancel_subscription_btn" value="Cancel Subscription">
                </form>

            </div>
            <!--end::Modal body-->

        </div>
    </div>
</div>
<!--end::Modal - Cancel Subscription-->
