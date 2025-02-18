@extends('dashboard.layouts.app')

@section('content')

@php

//dd($driver);
@endphp

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.drivers.partials.navbar')

        </div>
    </div>

    <div class="card mb-5 mb-xl-10">

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.overview')
        </div>

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.general')
        </div>

        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.drivers.sections.medical')
        </div>

    </div>

@endsection