@extends('dashboard.layouts.app')

@section('toolbar-buttons')
@endsection

@section('content')
<div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10" 
     style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('assets/media/illustrations/sketchy-1/4.png')">

    <div class="card-body pt-10 d-flex justify-content-center">
        <div class="w-50">
            <h2 class="mb-5 text-center">Buy Queries</h2>

            <form id="clearinghouseForm" method="POST" action="{{ route('dashboard.clearinghouse.buyqueries.process') }}">
                @csrf
                
                <div class="mb-5">
                    <label for="queryAmount" class="form-label">Query amount</label>
                    <input type="number" id="queryAmount" name="amount" class="form-control" min="1" value="1" required>
                </div>

                <div class="mb-5">
                    <label class="form-label">Price per Query:</label>
                    <div class="fw-bold text-primary fs-4" id="pricePerQuery">
                        {{ $price_per_query ?? 0 }}
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Total Cost:</label>
                    <div class="fw-bold text-success fs-4" id="totalCost">0</div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-150px">Buy</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for live calculations --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const queryInput = document.getElementById('queryAmount');
    const pricePerQuery = parseFloat(document.getElementById('pricePerQuery').textContent) || 0;
    const totalCost = document.getElementById('totalCost');

    function updateTotal() {
        const amount = parseInt(queryInput.value) || 0;
        totalCost.textContent = (amount * pricePerQuery).toFixed(2);
    }

    queryInput.addEventListener('input', updateTotal);
    updateTotal(); // initial
});
</script>
@endsection
