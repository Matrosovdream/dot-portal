@php
    $fields = [
        'dot_number' => [
            'label' => 'USDOT',
            'value' => $user['company']['dot_number'] ?? '-',
        ],
        'mc_number' => [
            'label' => 'MC Number',
            'value' => $user['company']['mc_number'] ?? '-',
        ],
        'last_mcs150_date' => [
            'label' => 'Last MCS-150 date',
            'value' => isset($company['saferweb']['latest_update']) ? dateFormat($company['saferweb']['latest_update']) : '-',
        ],
        'iss_score' => [
            'label' => 'ISS Score',
            'value' => '-',
        ],
    ];
@endphp


<div class="card card-flush h-lg-100">

    <div class="card-header pt-5">

        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Company information</span>
            <!--<span class="text-gray-500 mt-1 fw-semibold fs-6">Your credentials here</span>-->
        </h3>

        <div class="card-toolbar">
        </div>

    </div>

    <div class="card-body pt-5">

        @foreach( $fields as $field )
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-semibold fs-6 me-2">{{ $field['label'] }}</div>
                <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">
                        {{ $field['value'] }}
                    </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
        @endforeach

        <div class="d-flex flex-stack">
            <div class="text-gray-700 fw-semibold fs-6 me-2">
                <a href="{{ route('dashboard.profile.company.edit') }}" class="text-primary opacity-75-hover fs-6 fw-semibold">
                    Update credentials
                </a>
            </div>
            <div class="d-flex align-items-senter">

            </div>
        </div>

    </div>
</div>