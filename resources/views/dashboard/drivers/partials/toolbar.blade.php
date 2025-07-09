@section('toolbar-buttons')

    @if( $userSubscription['subscription'] )

        @if( $userSubscription['driversRemained'] != 0 )

            <a href="{{ route('dashboard.drivers.create') }}" class="btn btn-sm fw-bold btn-primary">
                New Driver
            </a>

        @else

            <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_sub_drivers">
                New Driver
            </a>

        @endif

    @else

        <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_sub_drivers">
            New Driver
        </a>

    @endif

    <a 
        href="{{ route('dashboard.drivers.terminated') }}" 
        class="btn btn-sm fw-bold btn-secondary" 
        >
        Terminated Drivers
    </a> 

@endsection