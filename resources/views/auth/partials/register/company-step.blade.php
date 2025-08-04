

<div class="pb-10 pb-lg-15">

    <h2 class="fw-bold d-flex align-items-center text-gray-900">
        Company information
        <span class="ms-1" data-bs-toggle="tooltip" title="Billing is issued based on your selected account typ">
            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
        </span>
    </h2>

    <div class="text-muted fw-semibold fs-6">If you need more info, please check out 
    <a href="#" class="link-primary fw-bold">Help Page</a>.</div>

</div>

<input type="hidden" name="is_custom_request" id="is_custom_request" value="false">

<div class="fv-row">
    <!--begin::Row-->
    <div class="row">

        <div class="fv-row mb-8 position-relative">
            <x-input-label for="usdot" :value="__('USDOT number')" />
            <x-text-input 
            id="usdot" 
            class="form-control bg-transparent {{ $errors->has('usdot') ? 'is-invalid' : '' }}" 
            type="text" 
            name="usdot" 
            :value="auth()->user()->company->dot_number ?? old('usdot')"
            required 
            />

            <span
                id="usdot-loader"
                class="spinner-border text-primary position-absolute top-50 end-0 d-none"
                style="margin-top: -3px; margin-right: 15px;"
                role="status"
                >
                <span class="visually-hidden">Loading...</span>
            </span>

        </div>

        <div class="fv-row mb-8">
            <x-input-label for="company_name" :value="__('Company name')" />
            <x-text-input 
                id="company_name" 
                class="form-control bg-transparent {{ $errors->has('company_name') ? 'is-invalid' : '' }}" 
                type="text" 
                name="company_name" 
                :value="auth()->user()->company->name ?? old('company_name')"
                required 
                />
        </div>

        <div class="fv-row mb-8">

            <div class="fv-row mb-5 ">
                <!--begin::Label-->
                <x-input-label for="trucks_number" :value="__('Number of trucks')" />
                <x-text-input 
                    id="trucks_number" 
                    class="form-control bg-transparent {{ $errors->has('trucks_number') ? 'is-invalid' : '' }} d-none" 
                    type="number"
                    name="trucks_number" 
                    :value="auth()->user()->company->trucks_number ?? old('trucks_number')"
                    required 
                />
            </div>
        
            <div class="d-flex flex-stack">
                <div id="slider_trucks_number_value" class="fs-7 fw-semibold text-muted"></div>
                <div id="slider_trucks_number_slider" class="noUi-sm w-100 ms-5 me-8"></div>
            </div>

        </div>

        <!-- Number of drivers -->
        <div class="fv-row mb-8">
            
            <div class="fv-row mb-5 ">
                <x-input-label for="drivers_number" :value="__('Number of drivers')" />
                <x-text-input 
                id="drivers_number" 
                class="form-control bg-transparent {{ $errors->has('drivers_number') ? 'is-invalid' : '' }} d-none" 
                type="number" 
                name="drivers_number" 
                :value="auth()->user()->company->drivers_number ?? old('drivers_number')"
                required 
                />
            </div>

            <div class="d-flex flex-stack">
                <div id="slider_drivers_number_value" class="fs-7 fw-semibold text-muted"></div>
                <div id="slider_drivers_number_slider" class="noUi-sm w-100 ms-5 me-8"></div>
            </div>

        </div>  

    </div>
    <!--end::Row-->


    <!--begin::Subscription Summary-->
    <div id="subscription-summary" class="card mt-10 d-none">
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

        <!--
        <form method="POST" action="#" id="custom-price-request-form">

            @csrf
            <input type="hidden" name="drivers_number" id="custom-drivers-number">
            <input type="hidden" name="trucks_number" id="custom-trucks-number">
            <input type="hidden" name="company_name" id="custom-company-name">
        -->

            <label for="custom_request_details" class="form-label fw-semibold">
                Describe your business needs or add any details
            </label>
            <textarea 
                id="custom_request_details" 
                name="request_details" 
                class="form-control" 
                rows="10" 
                placeholder="E.g., We operate across 5 states and need scalable pricing..."></textarea>

                <!--
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Request Custom Quote</button>
            </div>
        -->

        </form>

    </div>
    <!--end::Custom Price Request Form-->

</div>
<!--end::Input group-->

<div class="d-flex flex-stack pt-15 submit-block">
    <div class="mr-2">
        <a href="{{ route('register', ['step' => 'account']) }}" class="btn btn-lg btn-primary">
            <span class="indicator-label">Back</span>
        </a>
    </div>
    <div>
        <button type="submit" id="btn-continue" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
            Continue 
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



