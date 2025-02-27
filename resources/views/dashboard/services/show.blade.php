@extends('dashboard.layouts.app')

@section('content')

    <div class="d-flex flex-column flex-xl-row">

        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">

            @include('dashboard.services.sections.sidebar')
            
        </div>

        <div class="flex-lg-row-fluid ms-lg-15">

            @include('dashboard.services.sections.navbar')

            <div class="tab-content" id="myTabContent">

                @include('dashboard.services.sections.general')

            </div>

        </div>
    </div>

@endsection