<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Profile Details</h3>
        </div>

        <a href="{{ route('dashboard.drivers.show.profile', $driver['id']) }}" class="btn btn-sm btn-primary align-self-center">Edit Profile</a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['user']['firstname'] ?? '' }} {{ $driver['user']['lastname'] ?? '' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Phone</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['user']['phone'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Email</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['user']['email'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">DOB</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['dob'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">SSN</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['ssn'] ?? '-' }}
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