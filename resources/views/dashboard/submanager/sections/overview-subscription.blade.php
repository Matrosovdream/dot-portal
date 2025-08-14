@php
$items = [
    0 => [
        'title' => 'Status',
        'value' => $sub['status'],
    ],
    1 => [
        'title' => 'Subscription Name',
        'value' => $sub['subscription']['name'] ?? '',
    ],
    2 => [
        'title' => 'Drivers number',
        'value' => $sub['drivers_number']
    ],
    3 => [
        'title' => 'Price per driver',
        'value' => '$'.$sub['price_per_driver'],
    ],
    4 => [
        'title' => 'Total price',
        'value' => '$'.$sub['price'],
    ],
    5 => [
        'title' => 'Start date',
        'value' => dateFormat( $sub['start_date'] )
    ],
];
@endphp

<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Subscription</h3>
        </div>

        <a href="{{ route('dashboard.usersubscriptions.show.profile', $sub['id']) }}"
            class="btn btn-sm btn-primary align-self-center">
            Edit subscription
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