<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Medical card</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.drivers.show.medicalcard.update', $driver['id']) }}" enctype="multipart/form-data">

        @csrf

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Examiner name
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="examiner_name" class="form-control form-control-lg form-control-solid"
                        placeholder="Examiner name" value="{{ $driver['medicalCard']['examiner_name'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    National registry
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="national_registry" class="form-control form-control-lg form-control-solid"
                        placeholder="National registry"
                        value="{{ $driver['medicalCard']['national_registry'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Issue date
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="issue_date" class="form-control form-control-lg form-control-solid datepicker"
                        placeholder="Issue date" value="{{ $driver['medicalCard']['issue_date'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Expiration date
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="expiration_date" class="form-control form-control-lg form-control-solid datepicker"
                        placeholder="Expiration date" value="{{ $driver['medicalCard']['expiration_date'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Medical card
                </label>

                <div class="col-lg-4 fv-row">
                    <x-file-uploader 
                        :inputName="'medical_card'"
                        :value="($driver['documents']['groupType']['medical_card'][0]['file'] ?? null)"
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