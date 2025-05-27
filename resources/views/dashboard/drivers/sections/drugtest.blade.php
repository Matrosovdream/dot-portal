<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Driver drug test</h3>
        </div>

    </div>

    <form 
        id="kt_account_profile_details_form" 
        class="form" 
        method="POST"
        action="{{ route('dashboard.drivers.show.drugtest.update', $driver['id']) }}" 
        enctype="multipart/form-data"
        >

        @csrf

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Test date
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="test_date" class="form-control form-control-lg form-control-solid datepicker"
                        placeholder="Test date" value="{{ $driver['drugTest']['test_date'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Test document
                </label>

                <div class="col-lg-4 fv-row">
                    <x-file-uploader 
                        :inputName="'drugtest_file'"
                        :value="''"
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

