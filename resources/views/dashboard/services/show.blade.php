@extends('dashboard.layouts.app')

@section('content')

    <div class="d-flex flex-column flex-xl-row">

        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-250px min-w-250px mb-7 me-lg-10">

            @include('dashboard.services.sections.sidebar')
            
        </div>

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">

            @include('dashboard.services.sections.navbar')

            <div class="tab-content" id="myTabContent">

                @include('dashboard.services.sections.general')

                @include('dashboard.services.sections.custom-fields')

            </div>

        </div>
    </div>

    <!-- Create form field modal -->
    @include('dashboard.services.modals.create-form-field', 
	[
		'service' => $service, 
		'formFields' => $formFieldsRef['items']
	])

    <!-- Update form field modal -->
    @foreach($service['formFields']['items'] as $field)
		@php
			//dd($formFieldsRef['items']);
		@endphp
		@include('dashboard.services.modals.edit-form-field', 
		[
			'fieldValue' => $field,
			'formFields' => $formFieldsRef['items']
		])
	@endforeach

@endsection