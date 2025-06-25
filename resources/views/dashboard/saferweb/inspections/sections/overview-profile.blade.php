@php
    
    $fields = [
        [
            'label' => 'Unit VIN',
            'value' => $data['unit_vin'] ?? '-',
        ],
        [
            'label' => 'Report date',
            'value' => dateFormat($data['report_date']) ?? '-',
        ],
        [
            'label' => 'Report number',
            'value' => $data['report_number'] ?? '-',
        ],
        [
            'label' => 'Report state',
            'value' => $data['report_state'] ?? '-',
        ],
        [
            'label' => 'Inspection level',
            'value' => $data['inspection_level'] ?? '-',
        ],
    ];

@endphp

<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Main information</h3>
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