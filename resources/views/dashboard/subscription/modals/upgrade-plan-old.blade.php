
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

                <form method="POST" action="{{ route('dashboard.subscription.update') }}">
                    @csrf
 
                    <div class="d-flex flex-column">

                        <div class="nav-group nav-group-outline mx-auto" data-kt-buttons="true">
                            <button 
                                class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3 active"
                                data-kt-plan="month">
                                Monthly
                            </button>
                        </div>

                        <div class="row mt-10">

                            <div class="col-lg-6 mb-10 mb-lg-0">
                                <div class="nav flex-column">

                                    @foreach( $allSubscriptions['items'] as $sub )

                                        <label
                                            class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6
                                            @if( $subscription['subscription'] )
                                            {{ $sub['id'] == $subscription['subscription']['id'] ? 'active btn-active btn-active-primary' : '' }}
                                            @endif
                                            "
                                            data-bs-toggle="tab" 
                                            data-bs-target="#kt_upgrade_plan_{{ $sub['id'] }}"
                                            >

                                            <div class="d-flex align-items-center me-2">

                                                <div
                                                    class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        name="plan" 
                                                        @if( $subscription['subscription'] )
                                                        {{ $sub['id'] == $subscription['subscription']['id'] ? ' checked="checked"' : '' }}
                                                        @endif
                                                        value="{{ $sub['id'] }}" 
                                                        />
                                                </div>
        
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center fs-2 fw-bold flex-wrap">
                                                        {{ $sub['name'] }}
                                                    </div>
                                                    <div class="fw-semibold opacity-75">
                                                        {{ $sub['short_description'] }}
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="ms-5">
                                                <span class="mb-2">$</span>
                                                <span class="fs-3x fw-bold" data-kt-plan-price-month="{{ $sub['price'] }}"
                                                    data-kt-plan-price-annual="{{ $sub['price'] }}">{{ $sub['price'] }}</span>
                                                <span class="fs-7 opacity-50">/
                                                    <span data-kt-element="period">Mon</span>
                                                </span>
                                            </div>

                                        </label>

                                    @endforeach

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="tab-content rounded h-100 bg-light p-10">

                                    @foreach( $allSubscriptions['items'] as $sub )

                                        <div 
                                            class="tab-pane fade 
                                            @if( $subscription['subscription'] )
                                            {{ $sub['id'] == $subscription['subscription']['id'] ? 'show active' : '' }}
                                            @endif
                                             " 
                                            id="kt_upgrade_plan_{{ $sub['id'] }}"
                                            >

                                            <div class="pb-5">
                                                <h2 class="fw-bold text-gray-900">
                                                    {{ $sub['name'] }}
                                                </h2>
                                                <div class="text-muted fw-semibold">
                                                    {{ $sub['short_description'] }}
                                                </div>
                                            </div>

                                            <div class="pt-1">

                                                @foreach( $sub['points']['items'] as $point )
        
                                                    <div class="d-flex align-items-center mb-7">
                                                        <span class="fw-semibold fs-5 text-gray-700 flex-grow-1">
                                                            {{ $point['title'] }}
                                                        </span>
                                                        <i class="ki-duotone ki-check-circle fs-1 
                                                        {{ $point['included'] ? 'text-success' : 'text-danger' }}
                                                        ">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>

                                                @endforeach
        
                                            </div>

                                        </div>

                                    @endforeach

                                </div>
                            </div>

                        </div>
                    </div>

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

                    <div class="d-flex flex-center flex-row-fluid pt-12">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="kt_modal_upgrade_plan_btn">
                            <span class="indicator-label">Upgrade Plan</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>

                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const planRadios = document.querySelectorAll('input[name="plan"]');
        const upgradeBtn = document.getElementById('kt_modal_upgrade_plan_btn');
        const refundSummary = document.getElementById('upgrade-refund-summary');

        // Store the initially selected plan ID
        let initialPlanId = null;
        planRadios.forEach(radio => {
            if (radio.checked) {
                initialPlanId = radio.value;
            }
        });

        // Disable upgrade button initially
        upgradeBtn.disabled = true;

        // Listen for changes
        planRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value !== initialPlanId) {
                    upgradeBtn.disabled = false;
                    // Show refund summary if it exists
                    if (refundSummary) {
                        refundSummary.style.display = 'block';
                    }
                } else {
                    upgradeBtn.disabled = true;
                    // Hide refund summary if it exists
                    if (refundSummary) {
                        refundSummary.style.display = 'none';
                    }
                }
            });
        });
    });
</script>


