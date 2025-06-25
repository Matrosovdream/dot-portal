@php
    
    $vehicle = $data['api_data']['units_inspected'][0] ?? [];

    $fields = [
        [
            'label' => 'Vehicle VIN',
            'value' => $vehicle['unit_vin'] ?? '-',
        ],
        [
            'label' => 'Unit make',
            'value' => $vehicle['unit_make'] ?? '-',
        ],
        [
            'label' => 'Unit license',
            'value' => $vehicle['unit_license'] ?? '-',
        ],
        [
            'label' => 'Unit decal number',
            'value' => $vehicle['unit_decal_number'] ?? '-',
        ],
        [
            'label' => 'Unit license state',
            'value' => $vehicle['unit_license_state'] ?? '-',
        ],
        [
            'label' => 'Unit type',
            'value' => $vehicle['unit_type_description'] ?? '-',
        ],
    ];

@endphp

<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Vehicle</h3>
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