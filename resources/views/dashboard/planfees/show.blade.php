@extends('dashboard.layouts.app')

@section('content')

<div class="d-flex flex-column flex-xl-row">
    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
        <div class="card mb-5 mb-xl-8">
            
            <div class="card-body pt-15">
                
                <div class="d-flex flex-column mb-5">

                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                        {{ $fee['name'] }}
                    </a>
                    <a href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">
                        ${{ $fee['price'] }}
                    </a>
                    
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
                        
                        <form class="form" method="POST" action="{{ route('dashboard.planfees.update', $fee['id']) }}" id="kt_ecommerce_customer_profile">
                            @csrf

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Name</label>
                                <input 
                                    type="text" 
                                    name="name"
                                    value="{{ $fee['name'] }}" 
                                    class="form-control form-control-solid" 
                                    placeholder="" 
                                    disabled
                                    />
                                
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Price</label>
                                <input 
                                    type="number" 
                                    name="price"
                                    value="{{ $fee['price'] }}" 
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

                        </form>
                        
                    </div>
                    
                </div>
                
            </div>

        </div>
    </div>
</div>

@endsection