@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.profile.partials.navbar')

        </div>
    </div>

    @if(request()->routeIs('dashboard.profile.show'))

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.profile.sections.overview-profile')

            @include('dashboard.profile.sections.overview-company', ['company' => $user['company']])

        </div>

    @endif

    @if(request()->routeIs('dashboard.profile.edit'))

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.profile.sections.profile')

        </div>

    @endif

    @if(request()->routeIs('dashboard.profile.company'))

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.profile.sections.company', ['company' => $user['company']])

        </div>

    @endif

@endsection