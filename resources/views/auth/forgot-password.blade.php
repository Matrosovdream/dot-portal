<x-guest-layout>

    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST" action="{{ route('password.email') }}">

        @csrf

        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Forgot password</h1>
            <div class="text-gray-500 fw-semibold fs-6"></div>
        </div>

        <div class="fv-row mb-8">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control bg-transparent" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>


        <div class="d-grid mb-10">
            <x-primary-button class="btn btn-primary">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
 
        <div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
            <a href="{{ route('login') }}" class="link-primary fw-semibold">Sign in</a>
        </div>

    </form>

</x-guest-layout>



