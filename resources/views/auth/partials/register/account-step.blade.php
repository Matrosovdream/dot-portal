

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

    <div class="row">
        
        <div class="fv-row mb-8">
            <x-input-label for="name" :value="__('Official’s full name')" />
            <x-text-input 
                id="name" 
                class="form-control bg-transparent {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required autofocus autocomplete="name" 
                />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
            id="email" 
            class="form-control bg-transparent {{ $errors->has('email') ? 'is-invalid' : '' }}" 
            type="email" 
            name="email" 
            :value="old('email')" 
            required autocomplete="username" 
            />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="phone" :value="__('Phone number')" />
            <x-text-input 
            id="phone" 
            class="form-control bg-transparent {{ $errors->has('phone') ? 'is-invalid' : '' }}" 
            type="text" 
            name="phone" 
            :value="old('phone')" 
            required 
            />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input 
            id="password" 
            class="form-control bg-transparent {{ $errors->has('password') ? 'is-invalid' : '' }}"
            type="password"
            name="password"
            required autocomplete="new-password" 
            />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input 
            id="password_confirmation" 
            class="form-control bg-transparent {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
            type="password"
            name="password_confirmation" 
            required autocomplete="new-password" 
            />

        </div>

        <div class="fv-row mb-8">
            <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="toc" value="1" />
                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
                    I Accept the
                    <a href="#" class="ms-1 link-primary">Terms</a>
                </span>
            </label>
        </div>

    </div>

</div>

<div class="d-flex flex-stack pt-15">
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
        <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">Continue 
        <i class="ki-duotone ki-arrow-right fs-4 ms-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i></button>
    </div>
</div>



