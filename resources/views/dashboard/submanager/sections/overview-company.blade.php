@php
$items = [
    1 => [
        'title' => 'Company Name',
        'value' => $company['name'] ?? null,
    ],
    2 => [
        'title' => 'Dot Number',
        'value' => $company['dot_number'] ?? null,
    ],
    3 => [
        'title' => 'MC Number',
        'value' => $company['mc_number'] ?? null,
    ],
    4 => [
        'title' => 'Phone',
        'value' => $company['phone'] ?? null,
    ],
];
@endphp

<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Company information</h3>
        </div>

        <a href="{{ route('dashboard.usersubscriptions.show.company', $sub['id']) }}"
            class="btn btn-sm btn-primary align-self-center">
            Edit Company
        </a>

    </div>

    <div class="card-body p-9">

        @foreach( $items as $item )

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ $item['title'] }}</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">
                        {{ $item['value'] ?? '-' }}
                    </span>
                </div>
            </div>  

        @endforeach   

    </div>
</div>