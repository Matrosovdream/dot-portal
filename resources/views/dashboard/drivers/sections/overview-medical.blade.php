<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Medical card</h3>
        </div>

        <a href="{{ route('dashboard.drivers.show.medicalcard', $driver['id']) }}" class="btn btn-sm btn-primary align-self-center">Edit medical card</a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Examiner name</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['medicalCard']['examiner_name'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">National registry</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['medicalCard']['national_registry'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Issue date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['medicalCard']['issue_date'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Expiration date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['medicalCard']['expiration_date'] ?? '-' }}
                </span>
            </div>
        </div>

    </div>
</div>