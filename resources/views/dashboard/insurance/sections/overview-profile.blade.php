<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">General information</h3>
        </div>

        <a href="{{ route('dashboard.insurance-vehicles.show.profile', $insurance['id']) }}"
            class="btn btn-sm btn-primary align-self-center">Edit Profile</a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Number</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $insurance['number'] ?? '' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Start date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $insurance['start_date'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">End date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $insurance['end_date'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Document</label>
            <div class="col-lg-8">
                @if(!empty($insurance['file']))
                                <a href="#" class="btn btn-primary btn-sm flex-shrink-0 me-3" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_filepreview_{{ $insurance['file']['id'] }}">
                                    Preview
                                </a>
                                @include('dashboard.modals.layout.file-preview', [
                                    'file_id' => $insurance['file']['id'],
                                ])
                @else
                    <span class="fw-bold fs-6 text-gray-800">No document uploaded</span>
                @endif
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