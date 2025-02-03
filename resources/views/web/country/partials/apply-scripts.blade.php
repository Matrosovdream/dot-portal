
<script>
    $(document).ready(function () {

        // Add traveller logic
        var travellerCount = 1;
        $('#add_traveller').click(function () {

            // Clone the element and append it to the form
            var traveller = $('.card-traveller').first().clone();
            travellerCount++;
            traveller.find('h3').text('Traveler #' + travellerCount);
            $('.card-traveller').last().after(traveller);

            // clean the fields
            traveller.find('input').val('');

            // Birthday datepicker
            traveller.find(".birthday-date").removeClass("hasDatepicker").attr('id', 'birthday-' + travellerCount);

            // Passport expiration date datepicker
            traveller.find(".expiration-date").removeClass("hasDatepicker").attr('id', 'expiration-' + travellerCount);

            // Update datepicker
            traveller.find('.hasDatepicker').removeClass('hasDatepicker');
            updateDatePicker();

            // Update traveller count
            $('#traveller-count').text(travellerCount + ' travellers');
            $('input[name="quantity"]').val(travellerCount);

            // Update price with currency
            //calcTotals();

        });

        // Remove traveller logic
        $(document).on('click', '.btn-remove-traveller', function () {
            $(this).closest('.card-traveller').remove();
            travellerCount--;
            $('#traveller-count').text(travellerCount + ' travellers');
            $('input[name="quantity"]').val(travellerCount);
            calcTotals();
        });

        // Offer selection logic
        $('input[name="offer_id"]').change(function () {
            var price = $(this).data('price');
            $('input[name="offer_price_total"]').val(price);

            // set offer-price-span
            $('#offer-price-span').text(price + ' {{ $currency }}');
            $('#offer-price-span').data('price', price);

            calcTotals();
        });

        // Submit form
        $('.pay-button').click(function () {
            $('#multiStepForm').submit();
        });

        // Change extra-services-list logic checkbox, show show item on the right side
        $('input[name="extra_ids[]"]').change(function () {
            // If checked
            if ($(this).is(':checked')) {
                $('.service-' + $(this).val()).show();
            } else {
                $('.service-' + $(this).val()).hide();
            }

            calcTotals();

        });

    });

    function check_email(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    function calcTotals() {

        // Go through summary-table, if tr isn't hidden and find span with data-price
        var total_price = 0;
        $('.summary-table tr').each(function () {
            if (!$(this).hasClass('hidden')) {
                var price = parseFloat($(this).find('span').data('price'));
                console.log(price);

                if (isNaN(price)) {
                    price = 0;
                }
                total_price += price;
            }
        });

        // Update price with currency
        $('#total-price-span').text(total_price + ' ' + '{{ $currency }}');


        /*
        var price = parseFloat($('input[name="offer_price_total"]').val());
        var extras_price = parseFloat($('input[name="extras_price_total"]').val());
        var currency = '{{ $currency }}';
        var travellerCount = $('input[name="quantity"]').val();

        var total_price = price * travellerCount;

        // Add additional services price
        $('.extra-services-list input[name="extra_ids[]"]:checked').each(function () {
            //total_price += parseFloat($(this).data('price'));
        });


        // Update price with currency
        $('#price-span').text(total_price + ' ' + currency);
        //$('#extras-price-span').text(extras_price * travellerCount + ' ' + currency);
        */

    }

    // Phone validation func
    function validatePhone(phone) {
        var re = /^\d{10}$/;
        return re.test(phone);
    }

</script>




