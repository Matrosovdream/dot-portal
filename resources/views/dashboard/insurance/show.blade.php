@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.drivers.partials.navbar')

        </div>
    </div>

    @if( request()->routeIs('dashboard.drivers.show') )

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.drivers.sections.overview-profile')

            @include('dashboard.drivers.sections.overview-license')

            @include('dashboard.drivers.sections.overview-medical')

            @include('dashboard.drivers.sections.overview-drugtest')

            @php /*
            @include('dashboard.drivers.sections.overview-mvr')

            @include('dashboard.drivers.sections.overview-clearing')
            */ @endphp

        </div>

    @endif

    @if( request()->routeIs('dashboard.drivers.show.profile') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.profile')
        </div>

    @endif

    @if( request()->routeIs('dashboard.drivers.show.license') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.license')
        </div>

    @endif

    @if( request()->routeIs('dashboard.drivers.show.address') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.address')
        </div>

    @endif

    @if( request()->routeIs('dashboard.drivers.show.medicalcard') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.medical')
        </div>

    @endif

    @if( 
        request()->routeIs('dashboard.drivers.show.drugtest')
        )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.drugtest')
        </div>

    @endif

@endsection