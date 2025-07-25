<div class="pb-10 pb-lg-15">
    <h2 class="fw-bold d-flex align-items-center text-gray-900">
        Confirm Payment
        <span class="ms-1" data-bs-toggle="tooltip" title="Review your selected plan and registration fee">
            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
        </span>
    </h2>
    <div class="text-muted fw-semibold fs-6">
        Please review the payment details before proceeding.
    </div>
</div>

<!--begin::Summary Block-->
<div class="border rounded p-7 mb-10 bg-light-primary">
    <div class="d-flex flex-stack mb-4">
        <span class="fs-6 fw-semibold text-gray-700">Subscription Plan:</span>
        <span class="fs-6 fw-bold text-gray-900">$<span id="subscription_price">255</span> / month</span>
    </div>

    <div class="d-flex flex-stack mb-4">
        <span class="fs-6 fw-semibold text-gray-700">Registration Fee:</span>
        <span class="fs-6 fw-bold text-gray-900">$<span id="registration_fee">199</span></span>
    </div>

    <div class="separator my-5"></div>

    <div class="d-flex flex-stack">
        <span class="fs-5 fw-bold text-gray-800">Total:</span>
        <span class="fs-3 fw-bolder text-success">$<span id="total_amount">148</span></span>
    </div>
</div>
<!--end::Summary Block-->

<div class="d-flex flex-stack">
    <div class="mr-2">
        <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
        <i class="ki-duotone ki-arrow-left fs-4 me-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>Previous</button>
    </div>
    <div>
        <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="submit">
            <span class="indicator-label">Submit 
            <i class="ki-duotone ki-arrow-right fs-4 ms-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i></span>
            <span class="indicator-progress">Please wait... 
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
        <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
            Pay now
        <i class="ki-duotone ki-arrow-right fs-4 ms-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i></button>
    </div>
</div>
