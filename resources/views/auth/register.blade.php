@php
    $currentStep = request()->get('step', 'account');
@endphp

<x-register-layout>

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column stepper-multistep" id="kt_create_account_stepper">
            
            @include('auth.partials.register-sidebar')

            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <div class="d-flex flex-center flex-column flex-column-fluid">

                    <div class="w-lg-650px w-xl-700px p-10 p-lg-15 mx-auto">

                        <form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form" method="POST" action="{{ route('register') }}">
                            @csrf

                            <input type="hidden" name="step" value="{{ $currentStep }}" />

                            <div class="current" data-kt-stepper-element="content">
                        
                                <div class="w-100">

                                    @include("auth.partials.register.$currentStep-step")
                                    
                                </div>

                                <!--end::Wrapper-->
                            </div>
                        
                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

    @include('auth.partials.register-js')

</x-register-layout>