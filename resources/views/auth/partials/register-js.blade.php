<script>
    // Init single-value slider for Trucks
    const sliderTrucks = document.querySelector("#slider_trucks_number_slider");
    const sliderValue = document.querySelector("#slider_trucks_number_value");
    const hiddenInput = document.querySelector("#trucks_number");

    noUiSlider.create(sliderTrucks, {
        start: [{{ auth()->user()->company->trucks_number ?? old('trucks_number') }}],
        connect: [true, false],
        range: {
            min: 1,
            max: 1000
        },
        step: 1
    });

    sliderTrucks.noUiSlider.on("update", function (values) {
        const val = Math.round(values[0]);
        sliderValue.innerHTML = val + ' trucks';
        hiddenInput.value = val;

        const truckInput = document.querySelector("#trucks_number");
        if (truckInput) {
            truckInput.value = val;
        }
    });

    function setTruckNumber(value) {
        const sliderTrucks = document.querySelector("#slider_trucks_number_slider");
        const sliderValue = document.querySelector("#slider_trucks_number_value");
        const hiddenInput = document.querySelector("#trucks_number");

        if (sliderTrucks && sliderTrucks.noUiSlider) {
            sliderTrucks.noUiSlider.set(value);
            hiddenInput.value = value;
            sliderValue.innerHTML = value + ' trucks';
        }
    }

</script>

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
    const subscriptionTiers = @json($subs['items']);
</script>



<script>

    function updateSubscriptionSummary(driverCount) {
        const summaryCard = document.getElementById('subscription-summary');
        const description = document.getElementById('subscription-description');
        const total = document.getElementById('subscription-total');

        if (!summaryCard || !description || !total || !subscriptionTiers) return;

        let selectedTier = subscriptionTiers.find(tier => {
            return driverCount >= tier.drivers_amount_from && driverCount <= tier.drivers_amount_to;
        });

        // If no match, use the highest tier
        if (!selectedTier) {
            selectedTier = subscriptionTiers[subscriptionTiers.length - 1];
        }

        const pricePerDriver = selectedTier.price_per_driver;
        const subscriptionId = selectedTier.id;
        const tierLabel = selectedTier.name;
        const totalPrice = driverCount * pricePerDriver;

        // Update visible summary
        description.innerHTML = `
            <span class="text-gray-800">${driverCount} drivers</span> — 
            <span class="text-muted">${tierLabel}</span>
        `;
        total.innerText = `Total: $${totalPrice}/month`;
        summaryCard.classList.remove('d-none');

        // Update hidden inputs
        document.getElementById('price_per_driver').value = pricePerDriver;
        document.getElementById('subscription_price').value = totalPrice;
        document.getElementById('subscription_id').value = subscriptionId;
    }



    // Update subscription on slider change
    sliderDrivers.noUiSlider.on("update", function (values) {
        const val = Math.round(values[0]);
        sliderDriversValue.innerHTML = val + ' drivers';
        hiddenDriversInput.value = val;

        updateSubscriptionSummary(val);
    });


</script>


<script>
    document.getElementById('usdot').addEventListener('blur', function () {
        const usdot = this.value.trim();
    
        if (!usdot) return;
    
        // Show loader
        document.getElementById('usdot-loader').classList.remove('d-none');

        // Show loader
        const loader = document.getElementById('usdot-loader');
        loader.classList.remove('d-none');
    
        fetch('/retrieve-usdot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ usdot })
        })
        .then(response => response.json())
        .then(data => {
            // Fill the fields
            document.getElementById('company_name').value = data.company_name || '';
            document.getElementById('trucks_number').value = data.trucks_number || '';
            document.getElementById('drivers_number').value = data.drivers_number || '';

            if( data.trucks_number ) {
                setTruckNumber(data.trucks_number);
            }
            if( data.drivers_number ) {
                setDriverNumber(data.drivers_number);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Optional: show error to user
        })
        .finally(() => {
            setTimeout(() => {
                loader.classList.add('d-none');
            }, 100);
        });
    });
</script>