<x-guest-layout>

    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST" action="{{ route('register') }}">

        @csrf

        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
            <div class="text-gray-500 fw-semibold fs-6"></div>
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="name" :value="__('Official’s full name')" />
            <x-text-input id="name" class="form-control bg-transparent" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="email" :value="__('Email')" />
           <x-text-input id="email" class="form-control bg-transparent" type="email" name="email" :value="old('email')" required autocomplete="username" />
           <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="phone" :value="__('Phone number')" />
           <x-text-input id="phone" class="form-control bg-transparent" type="text" name="phone" :value="old('phone')" required />
           <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="usdot" :value="__('USDOT number')" />
           <x-text-input id="usdot" class="form-control bg-transparent" type="text" name="usdot" :value="old('usdot')" required />
           <x-input-error :messages="$errors->get('usdot')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="company_name" :value="__('Company name')" />
           <x-text-input id="company_name" class="form-control bg-transparent" type="text" name="company_name" :value="old('company_name')" required />
           <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="trucks_number" :value="__('Number of trucks')" />
           <x-text-input id="trucks_number" class="form-control bg-transparent" type="number" name="trucks_number" :value="old('trucks_number')" required />
           <x-input-error :messages="$errors->get('trucks_number')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="drivers_number" :value="__('Number of drivers')" />
           <x-text-input id="drivers_number" class="form-control bg-transparent" type="number" name="drivers_number" :value="old('drivers_number')" required />
           <x-input-error :messages="$errors->get('drivers_number')" class="mt-2" />
        </div>



        <div class="fv-row mb-8">
           <x-input-label for="password" :value="__('Password')" />

           <x-text-input id="password" class="form-control bg-transparent"
                           type="password"
                           name="password"
                           required autocomplete="new-password" />

           <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
           <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

           <x-text-input id="password_confirmation" class="form-control bg-transparent"
                           type="password"
                           name="password_confirmation" required autocomplete="new-password" />

           <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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
