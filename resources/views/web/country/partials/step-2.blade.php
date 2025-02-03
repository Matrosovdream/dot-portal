@if(isset($travellers) && count($travellers) > 0)
    @foreach($travellers as $key=>$traveller)

        <div class="card-traveller mt-25">

            <div class="row">
                <div class="col-md-6">
                    <h3>
                        {{ __('Traveler') }} #{{ $loop->iteration }}
                    </h3>
                </div>
                <div class="col-md-6 text-end">
                    <span class="btn-remove-traveller @if($loop->iteration == 1) hidden @endif">
                        <i class="bi bi-trash3 remove-traveller-icon"></i>
                    </span>
                </div>
            </div>

            @include('web.partials.fields-loop', 
            [
                'values' => $travellerFieldValues[$key], 
                'fields' => $formFields,
                'entity' => 'traveller',
                'travellerIndex' => $loop->iteration
            ])

            @php /*

            <div class="mb-3 xb-item--field">
                <label class="form-label w-100">
                    {{ __('First and middle name') }}
                </label>
                <input type="text" name="travellers[name][]" class="form-control w-75" value="{{ $traveller['name'] ?? '' }}"
                    placeholder="{{ __('First and middle name') }}"
                >
                <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
            </div>

            <div class="mb-3 xb-item--field">
                <label class="form-label w-100">
                    {{ __('Last name') }}
                </label>
                <input type="text" name="travellers[lastname][]" class="form-control w-75" value="{{ $traveller['lastname'] ?? '' }}"
                placeholder="{{ __('Last name') }}"
                >
                <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
            </div>

            <div class="mb-3 xb-item--field">
                <label for="birthday" class="form-label w-100">
                    {{ __('Birthday') }}
                </label>
                <input type="text" class="form-control w-50 datepicker-birthday birthday-date" name="travellers[birthday][]"
                    value="{{ $traveller['birthday'] ?? '' }}">
                <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
            </div>

            */ @endphp

            

        </div>

    @endforeach

@else

    <div class="card-traveller mt-25">

        <div class="row">
            <div class="col-md-6">
                <h3>
                    {{ __('Traveler') }} #1
                </h3>
            </div>
            <div class="col-md-6 text-end">
                <span class="btn-remove-traveller">
                    <i class="bi bi-trash3 remove-traveller-icon"></i>
                </span>
            </div>
        </div>

        @include('web.partials.fields-loop', 
        [
            'values' => [], 
            'fields' => $formFields,
            'entity' => 'traveller'
        ])

        @php /*
        <div class="mb-3 xb-item--field">
            <label class="form-label w-100">
                {{ __('First and middle name') }}
            </label>
            <input type="text" name="travellers[name][]" class="form-control w-75" 
            placeholder="{{ __('First and middle name') }}"
            >
            <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
        </div>

        <div class="mb-3 xb-item--field">
            <label class="form-label w-100">
                {{ __('Last name') }}
            </label>
            <input type="text" name="travellers[lastname][]" class="form-control w-75"
            placeholder="{{ __('Last name') }}"
            >
            <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
        </div>

        <div class="mb-3 xb-item--field">
            <label for="birthday" class="form-label w-100">
                {{ __('Birthday') }}
            </label>
            <input type="text" class="form-control w-50 datepicker-birthday birthday-date" name="travellers[birthday][]"
                id="birthday-1">
            <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
        </div>
        */ @endphp

    </div>

@endif

<div class="mb-3 xb-item--field">
    <button type="button" id="add_traveller" class="btn btn-primary w-100 mt-3">
        {{ __('Add traveller') }}
    </button>
</div>



<script>

    $(document).ready(function () {
        $('form.apply-form').submit(function (e) {
            // Validate step 1
            /*if (!validate_step2()) {
                e.preventDefault();
            }*/

            if (!validate_form()) {
                e.preventDefault();
            }

        });
    });

    function validate_form() {

        var isValid = true;

        // Check all fields inside form.apply-form and if it's required then after label etc
        // Check all fields and if not valid, show error label.error after the fields
        $('form.apply-form input.required').each(function () {
            if ($(this).val() == '') {
                $(this).next('label.error').remove(); // Remove previous error label
                $(this).after('<label class="error">This field is required</label>');
                isValid = false;
            } else {
                $(this).next('label.error').remove(); // Remove previous error label
            }
        });

        return isValid;

    }

    function validate_step2() {

        // Check all traveller fields
        var isValid = true;

        $('label.error').remove();

        // Check all fields and if not valid, show error label.error after the fields
        $('input[name^="travellers[name]"]').each(function () {
            if ($(this).val() == '') {
                $(this).after('<label class="error">This field is required</label>');
                isValid = false;
            }
        });

        $('input[name^="travellers[lastname]"]').each(function () {
            if ($(this).val() == '') {
                $(this).after('<label class="error">This field is required</label>');
                isValid = false;
            }
        });

        $('input[name^="travellers[birthday]"]').each(function () {
            if ($(this).val() == '') {
                $(this).after('<label class="error">This field is required</label>');
                isValid = false;
            }
        });

        $('input[name^="travellers[passport]"]').each(function () {
            if ($(this).val() == '') {
                $(this).after('<label class="error">This field is required</label>');
                isValid = false;
            }
        });

        // time_arrival validation, if selected in the next 5 days show message: 
        $('input[name="time_arrival"]').change(function () {

            var arrivalDate = $(this).val();
            var arrivalDateObj = new Date(arrivalDate);
            var today = new Date();
            var diffTime = arrivalDateObj - today;
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays < 5) {
                alert('You must select a date at least 5 days from today');
                $(this).val('');
            }

        });

        $('input[name="time_arrival"]').on('change', function () {
            const selectedDate = $(this).val();
            console.log("Selected date:", selectedDate);
        });

        return isValid;

    }

</script>