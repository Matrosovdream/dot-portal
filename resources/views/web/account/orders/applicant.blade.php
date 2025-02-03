@extends('web.layouts.app')

@section('content')

<div class="container my-4">

    @include('web.account.orders.partials.backlink', ['url' => route('web.account.order', $order->id)])

    <h3 class="mb-25">{{ __('My orders') }}</h3>

    <div class="row row-cols-1 row-cols-md-3 g-4">

        @foreach($orders as $order)

            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span class="card-status">{{ $order->status->name }}</span>
                            <span class="card-progress">{{ $order->getProgress() }}/4</span>
                        </div>
                        <h5 class="card-title mt-3">{{ $order->getProduct()->name }}</h5>
                        <div class="card-country mb-2">
                            <strong>{{ $order->countryTo()->name }}</strong>
                        </div>
                        <p class="card-text text-muted">#{{ $order->id }}</p>
                        <p class="card-text">{{ __('We need more information from you') }}</p>
                    </div>
                    <div class="card-footer bg-white mb-10 mt-10 border-0 d-flex justify-content-between align-items-center">
                        <a href="{{ route('web.account.order', $order->id) }}" class="text-decoration-none">
                            {{ __('Find out why') }}
                        </a>
                        <a href="{{ route('web.account.order', $order->id) }}" class="btn-arrow">&#10132;</a>
                    </div>
                </div>
            </div>

        @endforeach

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