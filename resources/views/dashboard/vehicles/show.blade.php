@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.vehicles.partials.navbar')

        </div>
    </div>

    @if( request()->routeIs('dashboard.vehicles.show') )

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.vehicles.sections.overview-profile')

            @include('dashboard.vehicles.sections.overview-license')

            @include('dashboard.vehicles.sections.overview-medical')

            @include('dashboard.vehicles.sections.overview-mvr')

            @include('dashboard.vehicles.sections.overview-clearing')

        </div>

    @endif

    @if( request()->routeIs('dashboard.vehicles.show.profile') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.profile')
        </div>

    @endif

    @if( request()->routeIs('dashboard.vehicles.show.license') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.license')
        </div>

    @endif

    @if( request()->routeIs('dashboard.vehicles.show.address') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.address')
        </div>

    @endif

    @if( request()->routeIs('dashboard.vehicles.show.medicalcard') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.medical')
        </div>

    @endif

@endsection