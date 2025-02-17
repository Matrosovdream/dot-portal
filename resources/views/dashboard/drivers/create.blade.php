@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">

        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true"
            aria-controls="kt_account_profile_details">

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Driver Details</h3>
            </div>

        </div>

        <div id="kt_account_settings_profile_details" class="collapse show">

            <form 
                id="kt_account_profile_details_form" 
                class="form"
                method="POST"
                action="{{ route('dashboard.drivers.store') }}"
                enctype="multipart/form-data"
                >

                <div class="card-body border-top p-9">

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Photo</label>

                        <div class="col-lg-8">

                            <div class="image-input image-input-outline" data-kt-image-input="true"
                                style="background-image: url('assets/media/svg/avatars/blank.svg')">
  
                                <div class="image-input-wrapper w-125px h-125px"
                                    style="background-image: url(assets/media/avatars/300-1.jpg)"></div>

                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change photo">
                                    <i class="ki-duotone ki-pencil fs-7">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>

                                    <input type="file" name="photo" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="photo_remove" />

                                </label>

                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel photo">
                                    <i class="ki-duotone ki-cross fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>

                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove photo">
                                    <i class="ki-duotone ki-cross fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>

                            </div>

                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>

                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Driver type</span>
                        </label>

                        <div class="col-lg-8 fv-row">
                            <select name="country" aria-label="Select a Country" data-control="select2"
                                data-placeholder="Select a country..."
                                class="form-select form-select-solid form-select-lg fw-semibold">
                                <option value="">Select a Driver type</option>

                                @foreach( $references['driverType']['items'] as $item )
                                    <option 
                                        value="{{ $item['id'] }}">
                                        {{ $item['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>

                        <div class="col-lg-8">
                            <div class="row">

                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="firstname"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="First name" value="{{ old('firstname') }}" />
                                </div>

                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="middlename"
                                        class="form-control form-control-lg form-control-solid" placeholder="Middle name"
                                        value="{{ old('middlename') }}" />
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label fw-semibold fs-6"></label>

                        <div class="col-lg-8">
                            <div class="row">

                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="lastname"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="Last name" 
                                        value="{{ old('lastname') }}" />
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone</label>

                        <div class="col-lg-8 fv-row">
                            <input 
                                type="text" 
                                name="phone" 
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Phone" 
                                value="{{ old('phone') }}" />
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>

                        <div class="col-lg-8 fv-row">
                            <input 
                                type="text" 
                                name="email" 
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Email" 
                                value="{{ old('email') }}" />
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">DOB</label>

                        <div class="col-lg-8 fv-row">
                            <input 
                                type="date" 
                                name="dob" 
                                class="form-control form-control-lg form-control-solid"
                                value="{{ old('dob') }}" />
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">SSN</label>

                        <div class="col-lg-8 fv-row">
                            <input 
                                type="text" 
                                name="ssn" 
                                class="form-control form-control-lg form-control-solid"
                                placeholder="SSN"
                                value="{{ old('ssn') }}" />
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Hire date</label>

                        <div class="col-lg-8 fv-row">
                            <input 
                                type="date" 
                                name="hire_date" 
                                class="form-control form-control-lg form-control-solid"
                                value="{{ old('hire_date') }}" />
                        </div>

                    </div>

                    

                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
                        Changes</button>
                </div>

            </form>

        </div>
    </div>

@endsection