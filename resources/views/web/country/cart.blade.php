@extends('web.layouts.app')

@section('content')

@php

    $steps = [
        'step-1' => __('Trip details'),
        'step-2' => __('Your info'),
        'step-3' => __('Passport'),
        'confirm' => __('Checkout'),
    ];

@endphp

<div class="container mt-2">

    <div class="row mb-10">
        <h1 class="">
            <span>{{ $product['Model']->name }} • {{ $product['Model']->getMeta('entries_number') }} entry </span>
        </h1>
    </div>

    <div class="row">

        <div class="step-indicator">
            @foreach($steps as $key => $step)
                <div id="step-indicator-{{ $key }}" class="step {{ $key == $template ? 'active' : '' }}">
                    {{ $step }}
                </div>
            @endforeach
        </div>

    </div>

    <form id="multiStepForm" class="xb-item--form contact-from apply-form" method="POST" action="{{ $action }}">
        @csrf

        <input type="hidden" name="next_page" value="{{ $next_page }}">
        <input type="hidden" name="cart_id" value="{{ $cart['fields']['id'] }}">

        <input type="hidden" name="extras_price_total" value="{{ $totals['extras_price_total'] }}" />
        <input type="hidden" name="offer_price_total" value="{{ $totals['offer_price_total'] }}" />
        <input type="hidden" name="quantity" value="{{ $cart['meta']['travellers_count'] ?? 1 }}" />


        <div class="row">

            <div class="col-md-8">


                <div id="step-1" class="form-step form-step-active">
                    <h3>{{ $subtitle }}</h3>

                    @include("web.country.partials.$template")

                    <br />

                    <a href="{{ $prev_page }}" class="btn btn-secondary">
                        {{ __('Previous step') }}
                    </a>
                </div>

                <div id="step-3" class="form-step">
                    <h3>{{ __('Checkout') }}</h3>
                    <p>{{ __('Confirm your details and proceed to checkout') }}</p>
                    <button type="button" class="btn btn-secondary" id="prev-3">
                        {{ __('Previous') }}
                    </button>
                    <button type="submit" class="btn btn-success">
                        {{ __('Save and Continue') }}
                    </button>
                </div>

    </form>
</div>

<!-- Sidebar Section -->
<div class="col-md-4">
    <div class="sidebar">

        <table class="table summary-table">
            <tbody>
                <tr>
                    <td>
                        <h5>{{ $product['Model']->name }}</h5>
                    </td>
                    <td>
                        <p id="traveler-count">{{ $cart['meta']['travellers_count'] ?? 1 }} {{ __('traveler(s)') }}</p>
                    </td>
                </tr>

                @foreach($product['Model']->getRequiredExtras() as $extra) 
                    <tr>
                        <td>+ {{ $extra->name }}</td>
                        <td>
                            <span 
                                id="extras-price-span" 
                                data-price="{{ $totals['extras_price_total'] }}"
                                >
                                    {{ $totals['extras_price_total'] }}
                                    {{ $currency }}
                            </span>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td>+ {{ __('Service fees') }}</td>
                    <td>
                        <span 
                            id="offer-price-span"
                            data-price="{{ $totals['offer_price_total'] }}"
                            >
                                {{ $totals['offer_price_total'] }} {{ $currency }}
                        </span>
                    </td>
                </tr>

                @foreach($extras['optional'] as $extra) 
                    <tr 
                        class="optional-service service-{{ $extra->id }} {{ !isset( $cart['extras'][ $extra->id ] ) ? "hidden" : '' }}"
                        >
                        <td>+ {{ $extra->name }}</td>
                        <td>
                            <span 
                                id="extras-price-span"
                                data-price="{{ $extra->price * count($travellers) }}"
                                >
                                {{ $extra->price * count($travellers) }}
                                {{ $currency }}
                            </span>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td>{{ __('Total price') }}</td>
                    <td>
                        <span id="total-price-span">{{ $totals['total_price'] }} {{ $currency }}</span>
                    </td>
                </tr>

            </tbody>
        </table>

        @if( !$next_page )
            <button type="submit" class="btn btn-primary w-100 mt-3">
                {{ __('Continue to Payment') }}
            </button>
        @else 
            <button type="submit" class="btn btn-primary w-100 mt-3">
                {{ __('Save and continue') }}
            </button>
        @endif

    </div>
</div>
</div>

</form>

</div>



<br />
<br />


@include('web.country.partials.apply-scripts')

@endsection