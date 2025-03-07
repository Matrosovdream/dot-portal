@extends('dashboard.layouts.app')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">

            @include('dashboard.servicerequest.history.partials.navbar')

        </div>
    </div>

    @if(request()->routeIs('dashboard.servicerequest.history.show'))

        <div class="collapse show">

            @include('dashboard.servicerequest.history.sections.overview')

        </div>

    @endif

@endsection