<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
    data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

    <div class="card-title m-0">
        <h3 class="fw-bold m-0">Medical card</h3>
    </div>

</div>

<form id="kt_account_profile_details_form" class="form" method="POST"
    action="{{ route('dashboard.drivers.update', $driver['id']) }}" enctype="multipart/form-data">

    @csrf

    @if($errors->any())

        <div class="alert alert-danger p-9">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    @endif

    <div class="card-body border-top p-9">

        <div class="row mb-6">

            <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                Examiner name
            </label>

            <div class="col-lg-4 fv-row">
                <input type="text" name="examiner_name" class="form-control form-control-lg form-control-solid" placeholder="Examiner name"
                    value="{{ $driver['medicalCard']['examiner_name'] }}" />
            </div>

        </div>

        <div class="row mb-6">

            <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                National registry
            </label>

            <div class="col-lg-4 fv-row">
                <input type="text" name="national_registry" class="form-control form-control-lg form-control-solid" placeholder="National registry"
                    value="{{ $driver['medicalCard']['national_registry'] }}" />
            </div>

        </div>

        <div class="row mb-6">

            <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                Issue date
            </label>

            <div class="col-lg-4 fv-row">
                <input type="date" name="issue_date" class="form-control form-control-lg form-control-solid" placeholder="Issue date"
                    value="{{ $driver['medicalCard']['issue_date'] }}" />
            </div>

        </div>

        <div class="row mb-6">

            <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                Expiration date
            </label>

            <div class="col-lg-4 fv-row">
                <input type="date" name="expiration_date" class="form-control form-control-lg form-control-solid" placeholder="Expiration date"
                    value="{{ $driver['medicalCard']['expiration_date'] }}" />
            </div>

        </div>



    </div>

    <div class="card-footer d-flex justify-content-end py-6 px-9">
        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
        <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
            Changes</button>
    </div>

</form>