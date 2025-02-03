@include('web.partials.fields-loop', 
[
    'values' => $cartFieldValues, 
    'fields' => $formFields,
    'entity' => 'order'
    ])

@php /*
<div class="mb-3 xb-item--field">

    <label for="arrivalDate" class="form-label w-100">
        {{ __('When do you arrive in') }}
        {{ $country->name }}?</label>
    <input type="text" class="form-control w-50 datepicker-min-today min-5-alert" name="time_arrival" id="arrivalDate"
        value="{{ $cart['meta']['time_arrival'] ?? '' }}">

    <span class="icon">
        <img src="{{ asset('/user/assets/img/icon/calendar.svg') }}" alt="">
    </span>

    <div class="alert hidden application-alert">
        <p class="text-danger min-5-alert-text">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <b>{{ __('Your trip is very soon') }}</b>
        </p>
        <p class="">
            {{ __('Please complete your application at once to get your documents as quickly as possible.') }}
        </p>
    </div>

</div>

<div class="mb-3 xb-item--field">

    <label for="arrivalDate" class="form-label w-100">
        {{ __('Which airport do you arrive?') }}
        {{ $country->name }}?</label>
    <div class="w-50">
        <select class="select2" name="dest_airport_id" id="dest_airport_id">
            <option selected disabled></option>
            @foreach($airports as $airport)
                <option value="{{ $airport->id }}" @if(isset($cart['meta']['dest_airport_id']) && $cart['meta']['dest_airport_id'] == $airport->id) selected @endif>
                    {{ $airport->name }} - {{ $airport->identity }}
                </option>
            @endforeach
        </select>
    </div>
    <p class="form-note">
        If your arrival point isn't listed, we can't process your request.
    </p>
</div>

<div class="mb-3 xb-item--field">
    <label for="full_name" class="form-label  w-100">
        {{ __('Your full name') }}
    </label>
    <input type="text" class="form-control w-75" id="full_name" name="full_name"
        value="{{ $cart['meta']['full_name'] ?? '' }}">
    <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
</div>

<div class="mb-3 xb-item--field">
    <label for="phone" class="form-label  w-100">
        {{ __('Phone number') }}
    </label>
    <input type="tel" class="form-control w-75" id="phone" name="phone" value="{{ $cart['meta']['phone'] ?? '' }}">
    <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_call.svg') }}" alt=""></span>
</div>

<div class="mb-3 xb-item--field">
    <label for="email" class="form-label  w-100">
        {{ __('Email address') }}
    </label>
    <input type="email" class="form-control w-75" id="email" name="email" placeholder="example@mail.com"
        value="{{ $cart['meta']['email'] ?? '' }}">
    <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_mail.svg') }}" alt=""></span>
    <p class="form-note">
        {{ __('We use this to create your account and send you updates about your application.') }}
    </p>
</div>


<br />
<div class="mb-3 xb-item--field">
    <label for="email" class="form-label  w-100">
        {{ __('I want to receive VisaTrips updates, product launches and personalized offers. I can opt out anytime.') }}
    </label>
    <div class="agree-updates">
        <input class="form-check" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">{{ __('Yes, I want to receive updates') }}</label>
    </div>
</div>
*/ @endphp


<script>

    $(document).ready(function() {
        $('form.apply-form').submit(function(e) {
            // Validate step 1
            if (!validate_step1()) {
                e.preventDefault();
            }
        });
    });

    function validate_step1() {

        // Manual validation
        var arrivalDate = $('#field-arrival_date').val();
        var fullName = $('#field-full_name').val();
        var phone = $('#field-phone_number').val();
        var email = $('#field-email').val();
        var country_to = $('#country_to').val();


        var isValid = true;

        $('label.error').remove();

        // Check all fields and if not valid, show error label.error after the fields
        if (arrivalDate == '') {
            $('#field-arrival_date').after('<label class="error">This field is required</label>');
            isValid = false;
        }

        if (fullName == '') {
            $('#field-full_name').after('<label class="error">This field is required</label>');
            isValid = false;
        }

        if (phone == '') {
            $('#field-phone_number').after('<label class="error">This field is required</label>');
            isValid = false;
        }

        
        if (email == '' || !check_email(email)) {
            $('#field-email').after('<label class="error">Check email</label>');
            isValid = false;
        }
        
        /*if (country_to == null) {
            $('#country_to').after('<label class="error">This field is required</label>');
            isValid = false;
        }*/

        // Validate phone
        if (!validatePhone(phone)) {
            //$('#phone').after('<label class="error">Check phone</label>');
            //isValid = false;
        }


        return isValid;

    }

</script>