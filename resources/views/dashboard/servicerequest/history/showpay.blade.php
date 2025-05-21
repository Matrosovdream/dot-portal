@extends('dashboard.layouts.app')

@section('content')

    @if(request()->routeIs('dashboard.servicerequest.history.showpay'))

        <div class="collapse show">

            @include('dashboard.servicerequest.history.sections.showpay')

        </div>

    @endif

@endsection