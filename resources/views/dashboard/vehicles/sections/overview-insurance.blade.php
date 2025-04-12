<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Insurance</h3>
        </div>

        <a href="{{ route('dashboard.vehicles.show.insurance', $vehicle['id']) }}" class="btn btn-sm btn-primary align-self-center">Edit MVR</a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Insurance</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $vehicle['insurance']['number'] ?? '-' }}
                </span>
            </div>
        </div>

        @if( isset( $validation['errors']['mvr'] ) )

            @include('dashboard.includes.alerts.default', [
                'title' => 'Important Notice',
                'text' => 'Please make sure that your profile is fully completed',
                'link' => '',
                'link_url' => '',
            ])

        @endif

    </div>
</div>