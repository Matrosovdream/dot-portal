
<div class="modal fade" id="kt_modal_upgrade_plan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded">

            <div class="modal-header justify-content-end border-0 pb-0">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body pt-0 pb-15 px-5 px-xl-20">

                <div class="mb-13 text-center">
                    <h1 class="mb-3">Upgrade a Plan</h1>
                    <div class="text-muted fw-semibold fs-5">If you need more info, please check
                        <a href="#" class="link-primary fw-bold">Pricing Guidelines</a>.
                    </div>
                </div>

                <div class="w-75 mx-auto">

                <form method="POST" action="{{ route('dashboard.subscription.update') }}">
                    @csrf

                    <input type="hidden" name="is_custom_request" id="is_custom_request" value="false">
                    <input type="hidden" name="price_per_driver" id="price_per_driver" value="">
                    <input type="hidden" name="subscription_price" id="subscription_price" value="">
                    <input type="hidden" name="subscription_id" id="subscription_id" value="">

                    <div class="row">

                        <!-- Number of drivers -->
                        <div class="fv-row mb-8">
                            
                            <div class="fv-row mb-5 ">
                                <x-input-label for="drivers_number" :value="__('Number of drivers')" />
                                <x-text-input 
                                id="drivers_number" 
                                class="form-control bg-transparent {{ $errors->has('drivers_number') ? 'is-invalid' : '' }} d-none" 
                                type="number" 
                                name="drivers_number" 
                                :value="$subscription['drivers_number'] ?? 1"
                                required 
                                />
                            </div>

                            <div class="d-flex flex-stack">
                                <div id="slider_drivers_number_value" class="fs-7 fw-semibold text-muted"></div>
                                <div id="slider_drivers_number_slider" class="noUi-sm w-100 ms-5 me-8"></div>
                            </div>

                        </div>  

                    </div>


                    <!--begin::Subscription Summary-->
                    <div id="subscription-summary" class="card mt-10">
                        <div class="card-body p-7 bg-light-primary">
                            <div class="d-flex align-items-center mb-4">
                                <i class="ki-duotone ki-wallet fs-1 text-primary me-4">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div>
                                    <h3 class="mb-0 fw-bold text-gray-900">Subscription Plan</h3>
                                    <div class="text-muted fs-6">Calculated based on number of drivers</div>
                                </div>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700 mb-2" id="subscription-description"></div>
                            <div class="fs-2 fw-bold text-primary" id="subscription-total"></div>
                        </div>
                    </div>
                    <!--end::Subscription Summary-->

                    <!--begin::Custom Price Request Form-->
                    <div id="custom-price-request-form" class="mt-5 d-none">

                        <label for="custom_request_details" class="form-label fw-semibold">
                            Describe your business needs or add any details
                        </label>
                        <textarea 
                            id="custom_request_details" 
                            name="request_details" 
                            class="form-control {{ $errors->has('request_details') ? 'is-invalid' : '' }}" 
                            rows="10" 
                            placeholder="E.g., We operate across 5 states and need scalable pricing...">{{ $subRequest->request_details ?? '' }}</textarea>

                    </div>
                    <!--end::Custom Price Request Form-->

                    @if( 
                        isset($subscription['refundSum']) &&
                        $subscription['refundSum'] > 0 
                        )

                        <!-- Refund Summary -->
                        <div class="mb-7" id="upgrade-refund-summary" style="display: none;">
                            <h4 class="fw-bold mb-2">Refund Summary</h4>
                            <p class="fs-5 text-gray-700">
                                You are about to upgrade your subscription. Upon confirmation, 
                                a refund of <strong>${{ $subscription['refundSum'] }}</strong> (Current Plan: {{ $subscription['subscription']['name'] }})
                                will be issued to your original payment method.
                            </p>
                        </div>

                    @endif

                    <div class="d-flex flex-stack pt-15 submit-block">
                        <div class="mr-2">
                            <a href="#" class="btn btn-lg btn-primary" data-bs-dismiss="modal">
                                <span class="indicator-label">Back</span>
                            </a>
                        </div>
                        <div>
                            <button type="submit" id="btn-continue" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
                                Update Plan
                                <i class="ki-duotone ki-arrow-right fs-4 ms-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                    
                            <button type="submit" id="btn-custom-request" class="btn btn-lg btn-primary d-none" data-kt-stepper-action="next">
                                Request Custom Quote
                                <i class="ki-duotone ki-arrow-right fs-4 ms-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                        </div>
                    </div>

                </form>

            </div>

            </div>

        </div>

    </div>

</div>





