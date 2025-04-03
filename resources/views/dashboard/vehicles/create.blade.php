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

                <div class="card-body border-top p-9">

                    @include('dashboard.includes.errors.default')

                    <div class="row mb-6">

                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            Profile photo
                        </label>

                        <div class="col-lg-4 fv-row">
                            <x-file-uploader :inputName="'profile_photo'" :value="''" :accept="'image/*'"
                                :multiple="false" :required="false" :label="'Upload file'" :note="'Upload 1 image'"
                                :description="''" />
                        </div>

                    </div>

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