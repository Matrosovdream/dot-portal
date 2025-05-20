<!--begin::Modal - Upgrade Subscription for Drivers-->
<div class="modal fade" id="kt_modal_add_sub_drivers" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h2 class="modal-title">Upgrade Your Plan</h2>
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
                    To add any drivers to your account, you'll need to upgrade your current subscription plan.
                </p>

                <br/>

                <div class="text-center">
                    <a href="{{ route('dashboard.subscription.index') }}" class="btn btn-primary">
                        Upgrade Subscription
                    </a>
                </div>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
<!--end::Modal - Upgrade Subscription for Drivers-->
