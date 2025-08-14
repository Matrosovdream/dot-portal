@extends('dashboard.layouts.app')

@section('content')

    <form class="form" method="POST" action="{{ route('dashboard.usersubscriptions.store') }}" id="kt_ecommerce_customer_profile">
        @csrf

        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                <div class="card mb-5 mb-xl-8">

                    <div class="card-body pt-5">

                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bold">Create new subscription</div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="flex-lg-row-fluid ms-lg-15">

                <div class="tab-pane fade active show" id="kt_ecommerce_customer_general" role="tabpanel">

                    @php /*
                    @include('dashboard.includes.errors.default')
                    */ @endphp

                    @include('dashboard.submanager.sections.create-user-block')

                    @include('dashboard.submanager.sections.create-company-block')

                    @include('dashboard.submanager.sections.create-sub-block')

                    <!-- Submit button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                            <span class="indicator-label">Save</span>
                            <span class="indicator-progress">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </form>

@endsection