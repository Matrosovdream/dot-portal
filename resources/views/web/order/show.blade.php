@extends('web.layouts.app')

@section('content')

    <div class="container mt-30 mb-30">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-20">
                    {{ __('Order Details') }} #{{ $order->id }}</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Product Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th class="text-center">{{ __('Quantity') }}</th>
                            <th>{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->getCart() as $item)
                            <tr>
                                <td>{{ $item['product']['name'] }} ({{ $item['offer']['name'] }})</td>
                                <td>{{ $item['offer']['price'] }} {{ $order->getCurrency() }}</td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td>{{ $item['offer']['total'] }} {{ $order->getCurrency() }}</td>
                            </tr>
                            @foreach( $item['extras'] as $extra )

                                <tr>
                                    <td>{{ $extra['name'] }}</td>
                                    <td>{{ $extra['price'] }} {{ $order->getCurrency() }}</td>
                                    <td class="text-center">{{ $extra['quantity'] }}</td>
                                    <td>{{ $extra['total'] }} {{ $order->getCurrency() }}</td>
                                </tr>

                            @endforeach    

                        @endforeach
                    </tbody>
                </table>
                <h3>{{ __('Total') }}: {{ $order->getTotal() }} {{ $order->getCurrency() }}</h3>

            </div>
        </div>

        @if( !$order->isPaid() )

            <div class="row mt-50">

                <h1 class="mb-20">{{ __('Payment') }}</h1>

                @include('web.order.payment')

            </div>

        @else

            <div class="row mt-50">

                <h1 class="mb-20">{{ __('Payment') }}</h1>

                <div class="alert alert-success">
                    {{ __('Payment has been paid successfully') }}
                </div>

            </div>

        @endif

    </div>

@endsection