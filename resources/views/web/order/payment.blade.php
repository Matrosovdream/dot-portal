<!-- Errors from flash -->
@if (session('error'))
    @foreach(session('error') as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach
@endif

<form class="xb-item--form contact-from w-50" action="{{ route('charge') }}" method="post">

    @csrf

    <input type="hidden" name="order_id" value="{{ $order->id }}" />

    <div class="row">
        <div class="col-lg-12">
            <div class="xb-item--field">
                <input type="text" name="cc_number" placeholder="Card Number" value="370000000000002" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="xb-item--field">
                <input type="text" name="expiry_month" placeholder="Month" value="12" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="xb-item--field">
                <input type="number" name="expiry_year" placeholder="Year" value="25" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="xb-item--field">
                <input type="number" name="cvv" placeholder="CVV" value="123" />
            </div>
        </div>
        <div class="col-12">
            <input type="submit" name="submit" value="Pay" class="thm-btn" />
        </div>
    </div>

</form>