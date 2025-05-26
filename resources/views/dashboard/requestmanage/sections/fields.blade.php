<div class="tab-pane fade active show" id="kt_ecommerce_customer_fields" role="tabpanel">
<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Request Details</h3>
        </div>

        @php /*
        <a href="{{ route('dashboard.profile.company') }}" class="btn btn-sm btn-primary align-self-center">
            Edit Company
        </a>
        */ @endphp

    </div>

    <div class="card-body p-9">

        @if( $formType == 'custom' )

            @foreach( $request['fieldValues']['items'] as $item )

                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        {{ $item['field']['title'] ?? '' }}
                    </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">
                            {{ $item['value'] ?? '-' }}
                        </span>
                    </div>
                </div>

            @endforeach

        @elseif( $formType == 'predefined' )

            @foreach( $request['predefinedValues']['items'] as $item )

                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        {{ $predefinedForm['fields'][ $item['field_code'] ]['title'] }}
                    </label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">
                            {{ $item['value'] ?? '-' }}
                        </span>
                    </div>
                </div>

            @endforeach

        @endif

    </div>

</div>

</div>

