@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.vehicles.partials.navbar')

        </div>
    </div>

    @if( request()->routeIs('dashboard.vehicles.show') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.overview-vehicle')
        </div>

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.overview-mvr')
        </div>

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.overview-insurance')
        </div>

    @endif

    @if( request()->routeIs('dashboard.vehicles.show.profile') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.profile')
        </div>

    @endif

    @if( request()->routeIs('dashboard.vehicles.show.mvr') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.mvr')
        </div>

    @endif

    <!-- insurance -->
    @if( request()->routeIs('dashboard.vehicles.show.insurance') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.insurance')
        </div>

    @endif

    <!-- inspections -->
    @if( request()->routeIs('dashboard.vehicles.show.inspections') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.vehicles.sections.inspections')
        </div>

    @endif

@endsection