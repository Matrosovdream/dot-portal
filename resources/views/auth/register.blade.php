<x-guest-layout>

    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST" action="{{ route('register') }}">

        @csrf

        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
            <div class="text-gray-500 fw-semibold fs-6"></div>
        </div>

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
            <x-input-label for="trucks_number" :value="__('Number of trucks')" />
           <x-text-input 
            id="trucks_number" 
            class="form-control bg-transparent {{ $errors->has('trucks_number') ? 'is-invalid' : '' }}" 
            type="number"
            name="trucks_number" 
            :value="old('trucks_number')" 
            required 
            />
        </div>

        <div class="fv-row mb-8">
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

        @php /*
           <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        */ @endphp
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
        
        <div class="d-grid mb-10">
            <x-primary-button class="btn btn-primary">
               {{ __('Register') }}
           </x-primary-button>
        </div>

        <div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
            <a href="{{ route('login') }}" class="link-primary fw-semibold">Sign in</a>
        </div>

    </form>

</x-guest-layout>


<!--begin::Input wrapper-->
<div class="w-lg-50">
    <!--begin::Label-->
    <label class="fs-6 fw-semibold mb-2">
        Daily Budget

        <span class="m2-1" data-bs-toggle="tooltip" title="Choose the budget allocated for each day. Higher budget will generate better results">
            <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        </span>
    </label>
    <!--end::Label-->

    <!--begin::Slider-->
    <div class="d-flex flex-column text-center">
        <div class="d-flex align-items-start justify-content-center mb-7">
            <span class="fw-bold fs-4 mt-1 me-2">$</span>
            <span class="fw-bold fs-3x" id="kt_modal_create_campaign_budget_label"></span>
            <span class="fw-bold fs-3x">.00</span>
        </div>
        <div id="kt_docs_forms_advanced_interactive_slider" class="noUi-sm"></div>
    </div>
    <!--end::Slider-->
</div>
<!--end::Input wrapper-->


<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.8.1/nouislider.min.js" integrity="sha512-g/feAizmeiVKSwvfW0Xk3ZHZqv5Zs8PEXEBKzL15pM0SevEvoX8eJ4yFWbqakvRj7vtw1Q97bLzEpG2IVWX0Mg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.8.1/nouislider.css" integrity="sha512-MKxcSu/LDtbIYHBNAWUQwfB3iVoG9xeMCm32QV5hZ/9lFaQZJVaXfz9aFa0IZExWzCpm7OWvp9zq9gVip/nLMg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>

var budgetSlider = document.querySelector("#kt_docs_forms_advanced_interactive_slider");
var budgetValue = document.querySelector("#kt_docs_forms_advanced_interactive_slider_label");

noUiSlider.create(budgetSlider, {
    start: [5],
    connect: true,
    range: {
        "min": 1,
        "max": 500
    }
});

budgetSlider.noUiSlider.on("update", function (values, handle) {
    budgetValue.innerHTML = Math.round(values[handle]);
    if (handle) {
        budgetValue.innerHTML = Math.round(values[handle]);
    }
});

</script>

<script>
    document.getElementById('usdot').addEventListener('blur', function () {
        const usdot = this.value.trim();
    
        if (!usdot) return;
    
        // Show loader
        document.getElementById('usdot-loader').classList.remove('d-none');
    
        fetch('/register.ttt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ usdot })
        })
        .then(response => response.json())
        .then(data => {
            // Fill the fields
            document.getElementById('company_name').value = data.company_name || '';
            document.getElementById('trucks_number').value = data.trucks_number || '';
            document.getElementById('drivers_number').value = data.drivers_number || '';
        })
        .catch(error => {
            console.error('Error:', error);
            // Optional: show error to user
        })
        .finally(() => {
            setTimeout(() => {
                loader.classList.add('d-none');
            }, 2000);
        });
    });
</script>
    






<?php /*
<x-guest-layout>
   <form method="POST" action="{{ route('register') }}">
       @csrf

       <!-- Name -->
       <div>
           <x-input-label for="name" :value="__('Name')" />
           <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
           <x-input-error :messages="$errors->get('name')" class="mt-2" />
       </div>

       <!-- Email Address -->
       <div class="mt-4">
           <x-input-label for="email" :value="__('Email')" />
           <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
           <x-input-error :messages="$errors->get('email')" class="mt-2" />
       </div>

       <!-- Password -->
       <div class="mt-4">
           <x-input-label for="password" :value="__('Password')" />

           <x-text-input id="password" class="block mt-1 w-full"
                           type="password"
                           name="password"
                           required autocomplete="new-password" />

           <x-input-error :messages="$errors->get('password')" class="mt-2" />
       </div>

       <!-- Confirm Password -->
       <div class="mt-4">
           <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

           <x-text-input id="password_confirmation" class="block mt-1 w-full"
                           type="password"
                           name="password_confirmation" required autocomplete="new-password" />

           <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
       </div>

       <div class="flex items-center justify-end mt-4">
           <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
               {{ __('Already registered?') }}
           </a>

           <x-primary-button class="ms-4">
               {{ __('Register') }}
           </x-primary-button>
       </div>
   </form>
</x-guest-layout>
