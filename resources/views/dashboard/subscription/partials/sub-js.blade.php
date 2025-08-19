
<script>

    // Drivers number slider
    const sliderDrivers = document.querySelector("#slider_drivers_number_slider");
    const sliderDriversValue = document.querySelector("#slider_drivers_number_value");
    const hiddenDriversInput = document.querySelector("#drivers_number");   

    noUiSlider.create(sliderDrivers, {
        start: [{{ auth()->user()->company->drivers_number ?? old('drivers_number', 1) }}],
        connect: [true, false],
        range: {
            min: 1,
            max: 1000
        },
        step: 1
    });

    sliderDrivers.noUiSlider.on("update", function (values) {
        const val = Math.round(values[0]);
        sliderDriversValue.innerHTML = val + ' drivers';
        hiddenDriversInput.value = val;

        const driverInput = document.querySelector("#drivers_number");
        if (driverInput) {
            driverInput.value = val;
        }
    });

    function setDriverNumber(value) {
        const sliderDrivers = document.querySelector("#slider_drivers_number_slider");
        const sliderDriversValue = document.querySelector("#slider_drivers_number_value");
        const hiddenDriversInput = document.querySelector("#drivers_number");

        if (sliderDrivers && sliderDrivers.noUiSlider) {
            sliderDrivers.noUiSlider.set(value);
            hiddenDriversInput.value = value;
            sliderDriversValue.innerHTML = value + ' drivers';

            updateSubscriptionSummary(value);
        }
    }

</script>



<script>

    const subscriptionTiers = @json($allSubscriptions['items']);

    console.log(subscriptionTiers);

    function updateSubscriptionSummary(driverCount) {
        const summaryCard = document.getElementById('subscription-summary');
        const description = document.getElementById('subscription-description');
        const total = document.getElementById('subscription-total');
        const customForm = document.getElementById('custom-price-request-form');

        const inputIsCustom = document.getElementById('is_custom_request');

        if (
            !summaryCard || 
            !description || 
            !total || 
            !customForm || 
            !inputIsCustom || 
            !subscriptionTiers
        ) return;

        let selectedTier = subscriptionTiers.find(tier => {
            return driverCount >= tier.drivers_amount_from && driverCount <= tier.drivers_amount_to;
        });

        // If no match, use the highest tier
        if (!selectedTier) {
            selectedTier = subscriptionTiers[subscriptionTiers.length - 1];
        }

        const { price_per_driver, id, name, is_custom_price } = selectedTier;
        const totalPrice = driverCount * price_per_driver;

        if (is_custom_price) {
            // Show custom request UI
            description.innerHTML = `
                <span class="text-gray-800">${driverCount} drivers</span> — 
                <span class="text-muted">${name}</span>
            `;
            total.innerHTML = `<span class="text-danger">Price by request</span>`;
            customForm.classList.remove('d-none');

            // Show request button, hide continue button
            btnCustomRequest.classList.remove('d-none');
            btnContinue.classList.add('d-none');

            inputIsCustom.value = 'true';
        } else {
            description.innerHTML = `
                <span class="text-gray-800">${driverCount} drivers</span> — 
                <span class="text-muted">${name}</span>
            `;
            total.innerText = `Total: $${totalPrice}/month`;
            customForm.classList.add('d-none');

            // Show continue button, hide request button
            btnCustomRequest.classList.add('d-none');
            btnContinue.classList.remove('d-none');

            inputIsCustom.value = 'false';
        }

        summaryCard.classList.remove('d-none');

        // Update hidden inputs
        document.getElementById('price_per_driver').value = price_per_driver;
        document.getElementById('subscription_price').value = is_custom_price ? '' : totalPrice;
        document.getElementById('subscription_id').value = id;
    }

    // Hook into slider update
    sliderDrivers.noUiSlider.on("update", function (values) {
        const val = Math.round(values[0]);
        sliderDriversValue.innerHTML = val + ' drivers';
        hiddenDriversInput.value = val;

        updateSubscriptionSummary(val);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const planRadios = document.querySelectorAll('input[name="plan"]');
        const upgradeBtn = document.getElementById('kt_modal_upgrade_plan_btn');
        const refundSummary = document.getElementById('upgrade-refund-summary');

        // Store the initially selected plan ID
        let initialPlanId = null;
        planRadios.forEach(radio => {
            if (radio.checked) {
                initialPlanId = radio.value;
            }
        });

        // Disable upgrade button initially
        upgradeBtn.disabled = true;

        // Listen for changes
        planRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value !== initialPlanId) {
                    upgradeBtn.disabled = false;
                    // Show refund summary if it exists
                    if (refundSummary) {
                        refundSummary.style.display = 'block';
                    }
                } else {
                    upgradeBtn.disabled = true;
                    // Hide refund summary if it exists
                    if (refundSummary) {
                        refundSummary.style.display = 'none';
                    }
                }
            });
        });
    });
</script>


<script>
    window.addEventListener('load', function() {
        const upgradeBtn = document.querySelector('[data-bs-target="#kt_modal_upgrade_plan"]');
        if (upgradeBtn) {
            upgradeBtn.click();
        }
    });
</script>
