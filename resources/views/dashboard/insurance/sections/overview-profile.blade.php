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
                    {{ dateFormat( $insurance['start_date'] ) ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">End date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ dateFormat( $insurance['end_date'] ) ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Uploaded document</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">

                    @php
                    $file = $insurance['file'] ?? null;
                    @endphp

                    @if( $file )
                        <a href="{{ $file['downloadUrl'] }}">
                            Download ({{ $file['title'] }})
                        </a>
                    @else
                        -
                    @endif
                    
                </span>
            </div>
        </div>

    </div>
</div>