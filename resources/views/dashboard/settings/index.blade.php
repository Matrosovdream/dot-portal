@extends('dashboard.layouts.app')

@section('content')

    <div class="card card-flush">
        <div class="card-body">

            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15">
                <li class="nav-item">
                    <a class="nav-link text-active-primary d-flex align-items-center pb-5 active" data-bs-toggle="tab"
                        href="#kt_ecommerce_settings_general">
                        <i class="ki-duotone ki-home fs-2 me-2"></i>General</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">

                    <form 
                        action="{{ route('dashboard.settings.store') }}" 
                        method="POST"
                        id="kt_ecommerce_settings_general_form" 
                        class="form"
                        >
                        @csrf

                        <div class="row mb-7">
                            <div class="col-md-9 offset-md-3">
                                <h2>General Settings</h2>
                            </div>
                        </div>

                        @foreach ($settings as $setting)

                            <div class="row fv-row mb-7">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span class="required">{{ $setting['title'] }}</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="{{ $setting['description'] }}">
                                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-9">

                                @if($setting['type'] == 'text')

                                    <input 
                                        type="text" 
                                        class="form-control form-control-solid" 
                                        id="{{ $setting['key'] }}" 
                                        name="{{ $setting['key'] }}"
                                        value="{{ $setting['value'] }}"
                                        >

                                @elseif($setting['type'] == 'textarea')

                                    <textarea 
                                        class="form-control form-control-solid" 
                                        id="{{ $setting['key'] }}"
                                        name="{{ $setting['key'] }}"
                                        >{{ $setting['value'] }}</textarea>

                                @endif

                                </div>
                            </div>
                        
                        @endforeach

                        <div class="row py-5">
                            <div class="col-md-9 offset-md-3">
                                <div class="d-flex">
                                    <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                        class="btn btn-light me-3">Cancel</button>
                                    <button type="submit" data-kt-ecommerce-settings-type="submit" class="btn btn-primary">
                                        <span class="indicator-label">Save</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection