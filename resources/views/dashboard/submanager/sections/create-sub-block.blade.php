
<div class="card pt-4 mb-6 mb-xl-9">

    <div class="card-header border-0">
        <div class="card-title">
            <h2>Subscription</h2>
        </div>
    </div>

    <div class="card-body pt-0 pb-5">

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Subscription</label>

            <select name="sub[subscription_id]" class="form-select form-select-solid form-select-lg mb-2">
                <option value="">Select Subscription</option>
                @foreach($subList['items'] as $subItem)
                    <option 
                        value="{{ $subItem['id'] }}" 
                        data-price-per-driver="{{ $subItem['price_per_driver'] }}"
                        data-drivers-from="{{ $subItem['drivers_amount_from'] }}"
                        data-drivers-to="{{ $subItem['drivers_amount_to'] }}"
                        {{ $subItem['id'] == old('sub.subscription_id') ? 'selected' : '' }}
                        >
                        {{ $subItem['name'] }}
                    </option>
                @endforeach
            </select>

            <!-- Error message -->
            @if (
                $errors->has('sub.subscription_id')
                )
                <span class="invalid-feedback" role="alert" style="display: block;">
                    <strong>{{ $errors->first('sub.subscription_id') }}</strong>
                </span>
            @endif

        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">Driver's Number</label>
            <input 
                    type="number" 
                    name="sub[drivers_number]" 
                    id="drivers_number"
                    class="form-control mb-2 {{ $errors->has('sub.drivers_number') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('sub.drivers_number') }}" 
                    />
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">Price Per Driver</label>
            <input 
                    type="number" 
                    name="sub[price_per_driver]" 
                    class="form-control mb-2 {{ $errors->has('sub.price_per_driver') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('sub.price_per_driver') }}" 
                    />
        </div>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const driversInput = document.getElementById('drivers_number');
        const subSelect = document.querySelector('select[name="sub[subscription_id]"]');
        const pricePerDriverInput = document.querySelector('input[name="sub[price_per_driver]"]');
    
        if (!driversInput || !subSelect || !pricePerDriverInput) return;
    
        function updateSubscriptionByDrivers() {
            const driversNumber = parseInt(driversInput.value, 10);
    
            if (!isNaN(driversNumber) && driversNumber > 0) {
                let matchedOption = null;
    
                Array.from(subSelect.options).forEach(option => {
                    const from = parseInt(option.dataset.driversFrom, 10);
                    const to = parseInt(option.dataset.driversTo, 10);
    
                    if (!isNaN(from) && !isNaN(to) && driversNumber >= from && driversNumber <= to) {
                        matchedOption = option;
                    }
                });
    
                if (matchedOption) {
                    pricePerDriverInput.value = matchedOption.dataset.pricePerDriver || '';
                    subSelect.value = matchedOption.value || '';
                } else {
                    pricePerDriverInput.value = '';
                    subSelect.value = '';
                }
            } else {
                pricePerDriverInput.value = '';
                subSelect.value = '';
            }
        }
    
        // Trigger on typing
        driversInput.addEventListener('keyup', updateSubscriptionByDrivers);
    
        // Also trigger when value is programmatically changed after USDOT fetch
        const origFetch = window.fetch;
        window.fetch = function (...args) {
            return origFetch.apply(this, args).then(response => {
                // Run after response is processed by your existing script
                setTimeout(updateSubscriptionByDrivers, 50);
                return response;
            });
        };
    });
    </script>
    
    