<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Profile Details</h3>
        </div>

        <a href="{{ route('dashboard.vehicles.show.profile', $vehicle['id']) }}" class="btn btn-sm btn-primary align-self-center">Edit Profile</a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Number</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $vehicle['number'] }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Vin</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $vehicle['vin'] }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Unit Type</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $vehicle['unitType']['name'] }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Ownership Type</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $vehicle['ownershipType']['name'] }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Driver</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ implode( ', ', [$vehicle['driver']['firstname'], $vehicle['driver']['lastname']] ) }}
                    ( {{ $vehicle['driver']['email'] }} )
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Registration expire date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ dateFormat( $vehicle['regExpireDate'] ) ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Inspection expire date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ dateFormat( $vehicle['inspectionExpireDate'] ) ?? '-' }}
                </span>
            </div>
        </div>

        @if( isset( $validation['errors']['general'] ) )

            @include('dashboard.includes.alerts.default', [
                'title' => 'Important Notice',
                'text' => 'Please make sure that your profile is fully completed',
                'link' => '',
                'link_url' => '',
            ])

        @endif

    </div>
</div>