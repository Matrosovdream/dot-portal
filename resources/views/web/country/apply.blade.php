@extends('web.layouts.app')

@section('content')


<div class="container mt-2">

    <div class="row mb-10">
        <h1 class="">
            <span>{{ $product->name }} • {{ $product->getMeta('entries_number') }} entry </span>
        </h1>
    </div>

    <div class="row">

        <div class="step-indicator">
            <div id="step-indicator-1" class="active">1 | {{ __('Trip details') }}</div>
            <div id="step-indicator-2">2 | {{ __('Your info') }}</div>
            <div id="step-indicator-3">3 | {{ __('Passport') }}</div>
            <div id="step-indicator-4">4 | {{ __('Checkout') }}</div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-8">
            <form id="multiStepForm" class="xb-item--form contact-from apply-form" method="POST"
                action="{{ route('web.order.create-apply') }}">

                @csrf

                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="country_to_id" value="{{ $country->id }}">
                <input type="hidden" name="country_to_code" value="{{ $country->code }}">
                <input type="hidden" name="country_from_id" value="{{ $countryFrom->id }}">
                <input type="hidden" name="country_from_code" value="{{ $countryFrom->code }}">

                <input type="hidden" name="product_price" value="{{ $totalPrice }}">
                <input type="hidden" name="product_extras_price" value="{{ $extrasPrice }}">

                <input type="hidden" name="currency" value="{{ $currency }}">
                <input type="hidden" name="quantity" value="1">

                <div id="step-1" class="form-step form-step-active">
                    <h3>Trip details</h3>

                    @include('web.country.partials.step-1')
                    
                    <button type="button" class="btn btn-primary" id="next-1">
                        {{ __('Next') }}
                    </button>
                </div>

                <div id="step-2" class="form-step form-step-active">

                    @include('web.country.partials.step-2')

                    <div class="mb-3 xb-item--field">
                        <button type="button" id="add_traveler" class="btn btn-primary w-100 mt-3">
                            {{ __('Add traveler') }}
                        </button>
                    </div>

                    <br />

                    <button type="button" class="btn btn-secondary" id="prev-2">
                        {{ __('Previous') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="next-2">
                        {{ __('Next') }}
                    </button>

                </div>

                <div id="step-3" class="form-step form-step form-step-active">
                    <h3>{{ __('Choose your processing time') }}</h3>

                    @include('web.country.partials.step-3')

                    <br />
                    <button type="button" class="btn btn-secondary" id="prev-3">
                        {{ __('Previous') }}
                    </button>

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
                                <h5>{{ $product->name }}</h5>
                            </td>
                            <td>
                                <p id="traveler-count">1 {{ __('traveler') }}</p>
                            </td>
                        </tr>

                        @foreach($product->extras as $extra) 
                            <tr>
                                <td>+ {{ $extra->name }}</td>
                                <td>
                                    <span id="extras-price-span">
                                        {{ $extra->price }}
                                        {{ $currency }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td>+ {{ __('Service fees') }}</td>
                            <td>
                                <span id="price-span">{{ $product->offers->first()->price }} {{ $currency }}</span>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <button type="button" class="btn btn-primary w-100 mt-3 pay-button">
                    {{ __('Pay order') }}
                </button>

            </div>
        </div>
    </div>
</div>

<br />
<br />


    @include('web.country.partials.apply-scripts')

@endsection