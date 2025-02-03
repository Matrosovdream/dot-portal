<div>
    <h5>{{ __('Completed Documents') }}</h5>
    <p>{{ __('View all applicants completed documents') }}</p>
</div>

@if( count($order->certificates) > 0 )
    <a href="{{ route('web.account.order.documents', $order->id) }}">
        <button class="btn btn-outline-primary">
            {{ __('View documents') }}
        </button>
    </a>
@endif