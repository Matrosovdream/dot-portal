<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">CDL License</h3>
        </div>

        <a href="{{ route('dashboard.drivers.show.cdl-license', $driver['id']) }}" class="btn btn-sm btn-primary align-self-center">
            Edit license
        </a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">License number</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['cdlLicense']['license_number'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Expiration date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ dateFormat( $driver['cdlLicense']['expiration_date'] ?? null ) ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Uploaded document</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">

                    @php
                    $file = $driver['cdlLicense']['file'] ?? null;
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

        @if( isset( $validation['errors']['cdlLicense'] ) )

            @include('dashboard.includes.alerts.default', [
                'title' => 'Important Notice',
                'text' => 'Please make sure that your profile is fully completed',
                'link' => '',
                'link_url' => '',
            ])

        @endif

    </div>
</div>