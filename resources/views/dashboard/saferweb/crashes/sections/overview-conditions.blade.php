@php
    
    $fields = [
        [
            'label' => 'Road surface',
            'value' => $data['api_data']['conditions']['road_surface'] ?? 0,
        ],
        [
            'label' => 'Weather',
            'value' => $data['api_data']['conditions']['weather'] ?? 0,
        ],
        [
            'label' => 'Light',
            'value' => $data['api_data']['conditions']['light'] ?? 0,
        ],
    ];

@endphp

<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Conditions</h3>
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