<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Driver license</h3>
        </div>

    </div>

    <form 
        id="kt_account_profile_details_form" 
        class="form" 
        method="POST"
        action="{{ route('dashboard.drivers.show.license.update', $driver['id']) }}" 
        enctype="multipart/form-data"
        >

        @csrf

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Driver's License Type
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="type_id" class="form-select form-select-lg form-select-solid" data-control="select2"
                        data-placeholder="Select..." data-hide-search="true">
                        <option></option>
                        @foreach($references['driverType']['items'] as $item)
                            <option 
                                value="{{ $item['id'] }}" 
                                {{ ($driver['license']['type_id'] ?? '') == $item['id'] ? 'selected' : '' }}
                                >
                                {{ $item['title'] }}
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Driver's License Endorsement
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="endorsement_id" class="form-select form-select-lg form-select-solid" data-control="select2"
                        data-placeholder="Select..." data-hide-search="true">
                        <option></option>
                        @foreach($references['licenseEndrs']['items'] as $item)
                            <option 
                                value="{{ $item['id'] }}" 
                                {{ ($driver['license']['endorsement_id'] ?? '') == $item['id'] ? 'selected' : '' }}
                                >
                                {{ $item['title'] }}
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    License number
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="license_number" class="form-control form-control-lg form-control-solid"
                        placeholder="License number" value="{{ $driver['license']['license_number'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Expiration date
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="expiration_date" class="form-control form-control-lg form-control-solid datepicker"
                        placeholder="Expiration date" value="{{ $driver['license']['expiration_date'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Driver's License State
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="state_id" class="form-select form-select-lg form-select-solid" data-control="select2"
                        data-placeholder="Select..." data-hide-search="true">
                        <option></option>
                        @foreach($references['state']['items'] as $state)
                            <option 
                                value="{{ $state['id'] }}"
                                {{ ($driver['license']['state_id'] ?? '') == $state['id'] ? 'selected' : '' }}
                                >
                                {{ $state['name'] }}
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    License document
                </label>

                <div class="col-lg-4 fv-row">
                    <x-file-uploader 
                        :inputName="'license_file'"
                        :value="($driverDocuments['license'][0]['file'] ?? '')"
                        :accept="'image/*,application/pdf'"
                        :multiple="false"
                        :required="false"
                        :label="'Upload file'"
                        :note="'Upload 1 image or PDF'"
                        :description="''"
                    />
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

