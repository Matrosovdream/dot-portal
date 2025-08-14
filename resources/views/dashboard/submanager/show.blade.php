@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.submanager.partials.navbar')

        </div>
    </div>

    @if( request()->routeIs('dashboard.usersubscriptions.show') )

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.submanager.sections.overview-subscription')

            @include('dashboard.submanager.sections.overview-user')

            @include('dashboard.submanager.sections.overview-company')

        </div>

    @endif

    @if( request()->routeIs('dashboard.usersubscriptions.show.profile') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.submanager.sections.profile')
        </div>

    @endif

    @if( request()->routeIs('dashboard.usersubscriptions.show.user') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.submanager.sections.user')
        </div>

    @endif

    @if( request()->routeIs('dashboard.usersubscriptions.show.company') )

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.submanager.sections.company')
        </div>

    @endif

@endsection