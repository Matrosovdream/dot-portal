@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.saferweb.crashes.partials.navbar')

        </div>
    </div>

    @if( request()->routeIs('dashboard.saferweb.crashes.show') )

        <div id="kt_account_settings_profile_details" class="collapse show">

            @include('dashboard.saferweb.crashes.sections.overview-profile')

        </div>

    @endif

@endsection