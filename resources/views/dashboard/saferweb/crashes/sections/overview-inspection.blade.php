@php
    
    $fields = [
        [
            'label' => 'Total injuries',
            'value' => $data['api_data']['total_injuries'] ?? 0,
        ],
        [
            'label' => 'Total fatalities',
            'value' => $data['api_data']['total_fatalities'] ?? 0,
        ],
        [
            'label' => 'Traffic description',
            'value' => $data['api_data']['trafficway_description'] ?? 0,
        ],
    ];

@endphp

<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Inspection</h3>
        </div>

    </div>

    <div class="card-body p-9">

        @foreach( $fields as $field )

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">
                    {{ $field['label'] }}
                </label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">
                        {{ $field['value'] }}
                    </span>
                </div>
            </div>

        @endforeach

    </div>
</div>