@extends('dashboard.layouts.app')

@section('content')

    <div class="d-flex flex-column flex-xl-row">

        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-250px min-w-250px mb-7 me-lg-10">

            @include('dashboard.requestmanage.sections.sidebar')
            
        </div>

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">

            @include('dashboard.requestmanage.sections.navbar')

            <div class="tab-content" id="myTabContent">

                @include('dashboard.requestmanage.sections.fields')

                @include('dashboard.requestmanage.sections.edit-fields')

                @include('dashboard.requestmanage.sections.history')

            </div>

        </div>
    </div>

@endsection