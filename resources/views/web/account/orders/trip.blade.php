@extends('web.layouts.app')

@section('content')

<div class="container my-4">

    @include('web.account.orders.partials.backlink', ['url' => route('web.account.order', $order->id)])

    <h2 class="mb-25">
        {{ $order->getProduct()->name }} - {{ __('Trip Details') }}
    </h2>

    <div class="row">
        <div class="col-md-3">
            @include('web.account.orders.partials.sidebar')
        </div>

        <div class="col-md-9">
            <!-- General Information Form -->
            <div class="card p-4">
                <h3 class="card-title mb-25">General Information</h3>

                <form method="POST" action="{{ route('web.account.order.trip.update', $order->id) }}"
                    class="xb-item--form contact-from w-75 apply-form">
                    @csrf

                    <input type="hidden" name="next_page" value="{{ $next_page ?? '' }}">

                    <div id="step-1" class="form-step form-step-active">

                        @include(
                            'web.partials.fields-loop',
                            [
                                'values' => $orderFieldValues,
                                'fields' => $formFields,
                                'entity' => 'order'
                            ]
                        )

                        @php /*
                        <div class="mb-3 xb-item--field">
                            <label for="phone" class="form-label  w-100">Phone number</label>
                            <input type="tel" class="form-control w-75" id="phone" name="phone"
                                value="{{ $order->getMeta('phone') }}">
                            <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_call.svg') }}" alt=""></span>
                        </div>

                        <div class="mb-3 xb-item--field">
                            <label for="arrivalDate" class="form-label w-100">When do you arrive in
                                {{ $order->countryTo()->name }}?</label>
                            <input type="text" class="form-control w-50 datepicker-min-today" name="time_arrival"
                                value="{{ $order->getMeta('time_arrival') }}">
                            <span class="icon"><img src="{{ asset('/user/assets/img/icon/location-2.svg') }}"
                                    alt=""></span>
                        </div>

                        <div class="mb-3 xb-item--field">

                            <label for="fromCountry" class="form-label">What country are you departing from?</label>
                            <div class="w-75">
                                <select class="select2" name="country_from">
                                    <option selected disabled></option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" @if($order->countryFrom()->code == $country->code)
                                        selected @endif>
                                            {{ $country->name }} - {{ $country->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        */ @endphp

                        <button type="submit" class="btn btn-primary" id="next-1">Save and continue</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<style>
    .card-body {
        background-color: rgb(248 249 249);
    }

    .card-status {
        background-color: #d1ecf1;
        color: #0c5460;
        border-radius: 10px;
        padding: 4px 8px 2px 8px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .card-progress {
        background-color: #e9ecef;
        border-radius: 50%;
        padding: 5px;
        font-size: 0.8rem;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-country img {
        width: 20px;
        margin-right: 5px;
    }

    .btn-arrow {
        background: linear-gradient(90deg, #00d7b0, #00e65b);
        color: white;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: none;
    }

    .card-text {
        font-size: 15px;
    }
</style>

@endsection