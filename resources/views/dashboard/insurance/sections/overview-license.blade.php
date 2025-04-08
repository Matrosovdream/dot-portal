<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Driver license</h3>
        </div>

        <a href="{{ route('dashboard.drivers.show.license', $driver['id']) }}" class="btn btn-sm btn-primary align-self-center">Edit licens</a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Driver type</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['license']['driverType']['title'] ?? '' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Endorsement type</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['license']['endorsement']['title'] ?? '' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">License number</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['license']['license_number'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">State</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['license']['state']['name'] ?? '' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Expiration date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['license']['expiration_date'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Uploaded document</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">

                    @php
                    $file = $driver['documents']['groupType']['license'][0] ?? null;
                    @endphp

                    @if( $file )
                        <a href="{{ $file['file']['downloadUrl'] }}">
                            {{ $file['title'] }}
                        </a>
                    @else
                        -
                    @endif
                    
                </span>
            </div>
        </div>

    </div>
</div>