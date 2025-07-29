

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
        
        <!--
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
        -->

        <div class="fv-row mb-8">
            <x-input-label for="firstname" :value="__('First name')" />
            <x-text-input 
                id="firstname" 
                class="form-control bg-transparent {{ $errors->has('firstname') ? 'is-invalid' : '' }}" 
                type="text" 
                name="firstname" 
                :value="auth()->user()->firstname ?? old('firstname')" 
                required autofocus
                />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="lastname" :value="__('Last name')" />
            <x-text-input 
                id="lastname" 
                class="form-control bg-transparent {{ $errors->has('lastname') ? 'is-invalid' : '' }}" 
                type="text" 
                name="lastname" 
                :value="auth()->user()->lastname ?? old('lastname')"
                required
                />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="email" :value="__('Email')" />

            @if( !auth()->check() )
                <x-text-input 
                id="email" 
                class="form-control bg-transparent {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                type="email" 
                name="email" 
                :value="auth()->user()->email ?? old('email')"
                required autocomplete="username" 
                />
            @else

                <x-text-input 
                id="email" 
                class="form-control bg-transparent {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                type="email" 
                name="email" 
                :value="auth()->user()->email ?? old('email')"
                required autocomplete="username" 
                disabled
                />

                <div class="mt-2">
                    If you want to change your email, we will remove this account, 
                    <a href="#" class="text-danger text-sm">click here</a>
                </div>

            @endif

            <!-- Error message -->
            @if (
                $errors->has('email') &&
                $errors->first('email') !== 'The email field is required.'
                )
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="phone" :value="__('Phone number')" />
            <x-text-input 
            id="phone" 
            class="form-control bg-transparent {{ $errors->has('phone') ? 'is-invalid' : '' }}" 
            type="text" 
            name="phone" 
            :value="auth()->user()->phone ?? old('phone')"
            required 
            />
        </div>

        @if( !auth()->check() )

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

        @endif

    </div>

</div>

<div class="d-flex flex-stack pt-15">
    <div class="mr-2">
    </div>
    <div>
        <button type="submit" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
            Continue
            <i class="ki-duotone ki-arrow-right fs-4 ms-1">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </button>
    </div>
</div>



