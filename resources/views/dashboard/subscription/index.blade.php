@extends('dashboard.layouts.app')

@php
    //dd($subscription);
@endphp

@section('content')

    <!-- Preview -->
    @include('dashboard.subscription.sections.preview')

    <!-- Payment Methods -->
    @include('dashboard.subscription.sections.payment-methods')

    <!-- Billing History -->
    @include('dashboard.subscription.sections.billing-history')

@endsection