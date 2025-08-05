@extends('dashboard.layouts.app')

@section('content')

<form class="form" method="POST" action="{{ route('dashboard.subrequests.update', $req['id']) }}" id="kt_ecommerce_customer_profile">
    @csrf

    <div class="d-flex flex-column flex-xl-row">
        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">

                <div class="card-body pt-5">

                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bold">
                            {{ $title }}
                        </div>
                    </div>

                    <div class="separator separator-dashed mb-5"></div>

                    <div class="d-flex flex-column mb-7">
                        <label class="fs-6 fw-semibold mb-2">Status</label>
                        <div class="fw-bold fs-6 text-gray-600">
                            <b>
                                {{ $statuses[ $req['status_id'] ] ?? '' }}    
                            </b>
                        </div>  
                    </div>

                    <div class="d-flex flex-column mb-7">
                        <label class="fs-6 fw-semibold mb-2">Client's request</label>
                        <div class="fw-bold fs-6 text-gray-600">
                            {{ $req['request_details'] }}
                        </div>  
                    </div>

                    @if( $req['status_id'] == 2 ) 

                        <div class="d-flex flex-column mb-7">
                            <label class="fs-6 fw-semibold mb-2">Do we email the user?</label>
                            <a href="http://127.0.0.1:8000/dashboard/servicefields/create" class="btn btn-sm fw-bold btn-primary">
                                Send email
                            </a>
                        </div>

                    @endif

                </div>

            </div>
        </div>

        <div class="flex-lg-row-fluid ms-lg-15">

            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">

                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                        href="#kt_ecommerce_customer_general">General Settings</a>
                </li>

            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade active show" id="kt_ecommerce_customer_general" role="tabpanel">
                    <div class="card pt-4 mb-6 mb-xl-9">

                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>General</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0 pb-5">

                            @include('dashboard.includes.errors.default')

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Status</label>
                                <select name="status_id" class="form-select form-select-solid" data-control="select2"
                                        data-placeholder="Select a group">
                                    <option></option>
                                    @foreach ($statuses as $id=>$name)
                                        <option 
                                            value="{{ $id }}"
                                            {{ $id == $req['status_id'] ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>                                        

                            <div id="price_per_driver_wrapper" class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Custom subscription price</label>
                                <input 
                                    type="number" 
                                    name="custom_price"
                                    value="{{ $req['userSubscription']['price_per_driver'] ?? old('custom_price') }}" 
                                    class="form-control form-control-solid" 
                                    placeholder="" 
                                />
                            </div>                            

                            <div class="d-flex justify-content-end">

                                <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                    class="btn btn-light-primary">
                                    <span class="indicator-label">Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>

                            </div>


                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</form>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('custom_price_checkbox');
        const priceWrapper = document.getElementById('price_per_driver_wrapper');

        function togglePriceField() {
            if (checkbox.checked) {
                priceWrapper.style.display = 'none'; // Hide if custom price is enabled
            } else {
                priceWrapper.style.display = 'block'; // Show otherwise
            }
        }

        checkbox.addEventListener('change', togglePriceField);
        togglePriceField(); // Initialize on page load
    });
</script>


@endsection