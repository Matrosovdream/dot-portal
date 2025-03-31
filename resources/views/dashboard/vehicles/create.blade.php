@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">

        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true"
            aria-controls="kt_account_profile_details">

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">New Vehicle</h3>
            </div>

        </div>

        <div id="kt_account_settings_profile_details" class="collapse show">

            <form 
                id="kt_account_profile_details_form" 
                class="form"
                method="POST"
                action="{{ route('dashboard.vehicles.store') }}"
                enctype="multipart/form-data"
                >
                @csrf

                @include('dashboard.includes.errors.default')

                <div class="card-body border-top p-9">

                    @php /*
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
                    */ @endphp

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Number</label>

                        <div class="col-lg-4 fv-row">
                            <input 
                                type="text" 
                                name="number" 
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Number" 
                                value="{{ old('number') }}" />
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Vin</label>

                        <div class="col-lg-4 fv-row">
                            <input 
                                type="text" 
                                name="vin" 
                                class="form-control form-control-lg form-control-solid"
                                placeholder="Vin" 
                                value="{{ old('vin') }}" />
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Unit type</span>
                        </label>

                        <div class="col-lg-4 fv-row">
                            <select name="unit_type_id" aria-label="Select a Unit type" data-control="select2"
                                data-placeholder="Select a Unit type"
                                class="form-select form-select-solid form-select-lg fw-semibold">
                                <option value="">Select a Unit type</option>

                                @foreach( $references['unitType']['items'] as $item )
                                    <option 
                                        value="{{ $item['id'] }}">
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span class="required">Ownership type</span>
                        </label>

                        <div class="col-lg-4 fv-row">
                            <select name="ownership_type_id" aria-label="Select aOwnership type" data-control="select2"
                                data-placeholder="Select a Ownership type"
                                class="form-select form-select-solid form-select-lg fw-semibold">
                                <option value="">Select a Ownership type</option>

                                @foreach( $references['ownershipType']['items'] as $item )
                                    <option 
                                        value="{{ $item['id'] }}"
                                        {{ $item['id'] == old('ownership_type_id') ? 'selected' : '' }}
                                        >
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            <span>Driver</span>
                        </label>

                        <div class="col-lg-4 fv-row">
                            <select name="driver_id" aria-label="Select driver" data-control="select2"
                                data-placeholder="Select driver"
                                class="form-select form-select-solid form-select-lg fw-semibold">
                                <option value="">Select driver</option>

                                @foreach( $references['driver']['items'] as $item )
                                    <option 
                                        value="{{ $item['id'] }}"
                                        {{ $item['id'] == old('driver_id') ? 'selected' : '' }}
                                        >
                                        {{ implode(' ', [$item['firstname'], $item['lastname']]) }}
                                        ({{ $item['email'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Registration expire date</label>

                        <div class="col-lg-4 fv-row">
                            <input 
                                type="date" 
                                name="reg_expire_date" 
                                class="form-control form-control-lg form-control-solid"
                                value="{{ old('reg_expire_date') }}" />
                        </div>

                    </div>

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Inspection expire date</label>

                        <div class="col-lg-4 fv-row">
                            <input 
                                type="date" 
                                name="inspection_expire_date" 
                                class="form-control form-control-lg form-control-solid"
                                value="{{ old('inspection_expire_date') }}" />
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