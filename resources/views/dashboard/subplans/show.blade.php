@extends('dashboard.layouts.app')

@section('content')

<form class="form" method="POST" action="{{ route('dashboard.subplans.update', $plan['id']) }}" id="kt_ecommerce_customer_profile">
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
                                <label class="fs-6 fw-semibold mb-2 required">Name</label>
                                <input 
                                    type="text" 
                                    name="name"
                                    value="{{ $plan['name'] ?? old('name') }}" 
                                    class="form-control form-control-solid" 
                                    placeholder="" 
                                    />
                                
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Slug</label>
                                <input 
                                    type="text" 
                                    name="slug"
                                    value="{{ $plan['slug'] ?? old('slug') }}" 
                                    class="form-control form-control-solid" 
                                    placeholder="" 
                                    />
                                
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Price per driver</label>
                                <input 
                                    type="number" 
                                    name="price_per_driver"
                                    value="{{ $plan['price_per_driver'] ?? old('price_per_driver') }}" 
                                    class="form-control form-control-solid" 
                                    placeholder="" 
                                    />
                                
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Driver's amount from</label>
                                <input 
                                    type="number" 
                                    name="drivers_amount_from"
                                    value="{{ $plan['drivers_amount_from'] ?? old('drivers_amount_from') }}"                
                                    class="form-control form-control-solid"
                                    placeholder=""
                                    />      
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Driver's amount to</label>
                                <input 
                                    type="number" 
                                    name="drivers_amount_to"
                                    value="{{ $plan['drivers_amount_to'] ?? old('drivers_amount_to') }}"                
                                    class="form-control form-control-solid"
                                    placeholder=""
                                    />      
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Short description</label>
                                <textarea 
                                    name="short_description"
                                    class="form-control form-control-solid"
                                    rows="3"
                                    placeholder="Short description">{{ $plan['short_description'] ?? old('short_description') }}</textarea>           
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

@endsection