<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Drug test</h3>
        </div>

        <a href="{{ route('dashboard.drivers.show.drugtest', $driver['id']) }}" class="btn btn-sm btn-primary align-self-center">Edit medical card</a>

    </div>

    <div class="card-body p-9">

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Test date</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">
                    {{ $driver['drugTest']['test_date'] ?? '-' }}
                </span>
            </div>
        </div>

        <div class="row mb-7">
            <label class="col-lg-4 fw-semibold text-muted">Edit drug test</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">

                    @php
                    $file = $driver['documents']['groupType']['medical_card'][0] ?? null;
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

        @if( isset( $validation['errors']['drugTest'] ) )

            @include('dashboard.includes.alerts.default', [
                'title' => 'Important Notice',
                'text' => 'Please make sure that your profile is fully completed',
                'link' => '',
                'link_url' => '',
            ])

        @endif

    </div>
</div>