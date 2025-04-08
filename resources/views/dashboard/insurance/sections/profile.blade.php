<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">General</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.insurance-vehicles.update', $insurance['id']) }}" enctype="multipart/form-data">

        @csrf

        <input type="hidden" name="action" value="update_general" />

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Title</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="name" class="form-control form-control-lg form-control-solid"
                        placeholder="Title" value="{{ $insurance['name'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Number</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="number" class="form-control form-control-lg form-control-solid"
                        placeholder="Number" value="{{ $insurance['number'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">Start date</label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="start_date" class="form-control form-control-lg form-control-solid"
                        value="{{ $insurance['start_date'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">End date</label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="end_date" class="form-control form-control-lg form-control-solid"
                        value="{{ $insurance['end_date'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">
                    Document
                </label>

                <div class="col-lg-4 fv-row">
                    <x-file-uploader :inputName="'document'" :value="''" :accept="'image/* application/pdf'"
                        :multiple="false" :required="false" :label="'Upload file'" :note="'Upload 1 image or PDF file'"
                        :description="''" />
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