@php

    $totals = $data['api_data']['violation_totals'] ?? [];
    
    $fields = [
        [
            'label' => 'Basic',
            'value' => $totals['basic'] ?? 1,
        ],
        [
            'label' => 'Hazmat',
            'value' => $totals['hazmat'] ?? 0,
        ],
        [
            'label' => 'Fatigued',
            'value' => $totals['fatigued'] ?? 0,
        ],
        [
            'label' => 'Driver Fitness',
            'value' => $totals['driver_fitness'] ?? 0,
        ],
        [
            'label' => 'Unsafe Driving',
            'value' => $totals['unsafe_driving'] ?? 0,
        ],
        [
            'label' => 'Vehicle Maintenance',
            'value' => $totals['vehicle_maintenance'] ?? 1,
        ],
        [
            'label' => 'Substances or Alcohol',
            'value' => $totals['substances_or_alcohol'] ?? 0,
        ],
    ];

@endphp

<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Violations</h3>
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