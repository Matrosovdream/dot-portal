<div class="d-flex justify-content-between mb-40">
    <h4>{{ __('Order Details') }}</h4>
    @php /*
    <a href="#" class="text-primary">{{ __('View Invoice') }}</a>
    */ @endphp
</div>
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
            @foreach($item['extras'] as $extra)

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

<h3>{{ __('Total') }}: {{ $total }} {{ $order->getCurrency() }}</h3>
