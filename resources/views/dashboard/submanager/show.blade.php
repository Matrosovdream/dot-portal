@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.insurance.partials.navbar')

        </div>
    </div>

    @if( request()->routeIs('dashboard.insurance-vehicles.show') )

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.insurance.sections.overview-profile')

        </div>

    @endif

    @if( request()->routeIs('dashboard.insurance-vehicles.show.profile') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.insurance.sections.profile')
        </div>

    @endif

@endsection