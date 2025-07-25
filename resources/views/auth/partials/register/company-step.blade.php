

<div class="pb-10 pb-lg-15">

    <h2 class="fw-bold d-flex align-items-center text-gray-900">
        Account information
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
            :value="old('usdot')" 
            required 
            />

            <span
                id="usdot-loader"
                class="spinner-border text-primary position-absolute top-50 end-0 d-none"
                style="margin-top: -3px; margin-right: 8px;"
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
                :value="old('company_name')" 
                required 
                />
        </div>

        <div class="fv-row mb-8">
        <div class="fv-row mb-10">
            <!--begin::Label-->
            <x-input-label for="trucks_number" :value="__('Number of trucks')" />
            <x-text-input 
            id="trucks_number" 
            class="form-control bg-transparent {{ $errors->has('trucks_number') ? 'is-invalid' : '' }}" 
            type="number"
            name="trucks_number" 
            :value="old('trucks_number')" 
            required 
            />
            <br/><br/>
        
            <!-- Hidden input to sync with slider -->
            <x-text-input 
                id="trucks_number" 
                class="form-control bg-transparent {{ $errors->has('trucks_number') ? 'is-invalid' : '' }} d-none" 
                type="number"
                name="trucks_number" 
                :value="old('trucks_number', 10)" 
                required 
            />
        
            <div class="d-flex flex-stack">
                <div id="slider_trucks_number_value" class="fs-7 fw-semibold text-muted"></div>
                <div id="slider_trucks_number_slider" class="noUi-sm w-100 ms-5 me-8"></div>
            </div>

        </div>

        <div class="fv-row mb-8">
        <!-- Number of drivers -->
        <div class="fv-row mb-10">
            <x-input-label for="drivers_number" :value="__('Number of drivers')" />
            <x-text-input 
            id="drivers_number" 
            class="form-control bg-transparent {{ $errors->has('drivers_number') ? 'is-invalid' : '' }}" 
            type="number" 
            name="drivers_number" 
            :value="old('drivers_number')" 
            required 
            />
        </div>
            <br/><br/>

            <x-text-input 
                id="drivers_number" 
                class="form-control bg-transparent {{ $errors->has('drivers_number') ? 'is-invalid' : '' }} d-none" 
                type="number" 
                name="drivers_number" 
                :value="old('drivers_number', 10)" 
                required 
            />

            <div class="d-flex flex-stack">
                <div id="slider_drivers_number_value" class="fs-7 fw-semibold text-muted"></div>
                <div id="slider_drivers_number_slider" class="noUi-sm w-100 ms-5 me-8"></div>
            </div>
        </div>  

    </div>
    <!--end::Row-->
</div>
<!--end::Input group-->


