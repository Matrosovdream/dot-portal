<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Company Details</h3>
        </div>

        <a href="{{ route('dashboard.profile.company') }}" class="btn btn-sm btn-primary align-self-center">
            Edit Company
        </a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Company Name</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $company['name'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Company Phone Number</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $company['phone'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">DOT Number</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $company['dot_number'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">MC Number</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $company['mc_number'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Business Address</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $company['addresses']['business']['full_address'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Mailing Address</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $company['addresses']['mailing']['full_address'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">

            <i class="ki-duotone ki-information fs-2tx text-warning me-4">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>

            <div class="d-flex flex-stack flex-grow-1">

                <div class="fw-semibold">
                    <h4 class="text-gray-900 fw-bold">We need your attention!</h4>
                    <div class="fs-6 text-gray-700">Some information here!
                        <a class="fw-bold" href="account/billing.html">Add something here</a>.
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>