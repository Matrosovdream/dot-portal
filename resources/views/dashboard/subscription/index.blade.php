@extends('dashboard.layouts.app')

@php
    //dd($subscription);
@endphp

@section('content')

    <!-- Registration Payment Section -->
    @include('dashboard.subscription.sections.registration-payment')

    <!-- Preview -->
    @include('dashboard.subscription.sections.preview')

    <!-- Payment Methods -->
    @include('dashboard.subscription.sections.payment-methods')

    <!-- Billing History -->
    @if( isset($paymentHistory) && $paymentHistory['items']->count() > 0 )
        @include('dashboard.subscription.sections.billing-history')
    @endif

    <!-- Subscription Modal -->
    @include('dashboard.subscription.modals.upgrade-plan')

@endsection