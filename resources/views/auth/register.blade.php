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

                        <form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form">

                            <input type="hidden" name="step" value="{{ $currentStep }}" />

                            <div class="current" data-kt-stepper-element="content">
                        
                                <div class="w-100">

                                    @include("auth.partials.register.$currentStep-step")

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