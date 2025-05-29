<div class="card">
    <div class="card-body py-20">
        <div class="mw-lg-950px mx-auto w-100">

            @include('dashboard.includes.errors.default')

            <!-- Header -->
            <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">INVOICE</h4>
                <div class="text-sm-end fw-semibold fs-4 text-muted mt-7">
                    <div>{{ siteSettings()['address'] ?? '' }}</div>
                </div>
            </div>

            <!-- Service Info Table -->
            <div class="table-responsive mb-5">
                <table class="table table-bordered align-middle">
                    <thead class="table-light fw-bold text-muted">
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th class="text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $service['name'] }}</td>
                            <td>{{ $service['description'] }}</td>
                            <td class="text-end">${{ $service['price'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payment Section -->
            <div class="mt-lg-10 pt-13">
                @if ($paymentCards['items']->count() === 0)
                    <div class="text-center mt-10">
                        <h4>No payment methods found</h4>
                        <a href="{{ route('dashboard.subscription.index') }}" class="btn btn-primary mt-4">
                            Add Payment Method
                        </a>
                    </div>
                @else
                    <form action="{{ route('dashboard.servicerequest.history.showpayprocess', $request['id']) }}" method="POST" class="text-center">
                        @csrf

                        <div class="mb-6">
                            <label class="form-label fw-bold fs-4 mb-3">Choose Payment Method</label>
                            <div class="mx-auto w-200" style="max-width: 200px;">
                                <select name="payment_method" class="form-select form-select-solid"  data-control="select2" data-placeholder="Select">
                                    @foreach ($paymentCards['items'] as $method)
                                        <option value="{{ $method['id'] }}">*** {{ $method['card_number'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg px-20">Pay</button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>
