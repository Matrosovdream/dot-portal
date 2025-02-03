@if(isset($travellers) && count($travellers) > 0)

    @foreach($travellers as $key=>$traveller)

        @php
        
        if( !isset( $traveller['passport_issue_country'] ) ) {
            $traveller['passport_issue_country'] = $countryFrom->slug;
        }

        @endphp

        <div class="card-traveller mt-25">

            <div class="row">
                <div class="col-md-6">
                    @php /*
                    <h3>
                        {{ $traveller['name'] }} {{ $traveller['lastname'] }} - {{ __('Passport information') }}
                    </h3>
                    */ @endphp
                    <h3>
                        Traveller #{{ $loop->iteration }} - {{ __('Passport information') }}
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

            <!-- Nationality -->
            @php /*
            <div class="mb-4 w-75">
                <label for="nationality" class="form-label">
                    {{ __("Nationality on passport") }}
                </label>
                <select class="select2" name="travellers[passport_issue_country][]">
                    <option>
                        {{ __("Choose country") }}
                    </option>
                    @foreach($countries as $country)
                        <option 
                            value="{{ $country->slug }}" 
                            data-slug="{{ $country->slug }}" 
                            @if(
                                isset($traveller['passport_issue_country']) && $country->slug == $traveller['passport_issue_country']
                                ) selected @endif
                            >
                            {{ $country->name }} - {{ $country->code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 xb-item--field">
                <input 
                    type="checkbox" 
                    name="travellers[skip_pass][]" 
                    class="form-control-checkbox" 
                    @if( isset($traveller['skip_pass']) && $traveller['skip_pass'] == 'true' ) checked @endif
                    id="traveller-skip-pass-{{ $loop->iteration }}"
                    value="true"
                    />
                <label class="form-label w-75" for="traveller-skip-pass-{{ $loop->iteration }}">
                    {{ __('Skip entering passport information for now') }}
                </label>
            </div>

            <div class="mb-3 xb-item--field traveller-pass-{{ $loop->iteration }}">
                <label for="arrivalDate" class="form-label w-100">
                    {{ __('Passport number') }}
                </label>
                <input 
                    type="text" 
                    name="travellers[passport][]" 
                    value="{{ $traveller['passport'] ?? '' }}"
                    class="form-control w-75" 
                    >
                <span class="icon"><img src="{{ asset('/user/assets/img/icon/c_user.svg') }}" alt=""></span>
            </div>

            <div class="mb-3 xb-item--field traveller-pass-{{ $loop->iteration }}">
                <label for="birthday" class="form-label w-100">
                    {{ __('Passport expiration date') }}
                </label>
                <input 
                    type="text" 
                    name="travellers[passport_expiration_date][]" 
                    value="{{ $traveller['passport_expiration_date'] ?? '' }}"
                    class="form-control w-50 datepicker-min-today expiration-date"
                    />
                <span class="icon"><img src="{{ asset('/user/assets/img/icon/calendar.svg') }}" alt=""></span>
            </div>

            <!-- Country of birth -->
            <div class="mb-4 w-75 traveller-pass-{{ $loop->iteration }}">
                <label for="nationality" class="form-label">
                    {{ __("Country of birth") }}
                </label>
                <select class="select2" name="travellers[birth_country][]">
                    <option>
                        {{ __("Choose country") }}
                    </option>
                    @foreach($countries as $country)
                        <option 
                            value="{{ $country->slug }}" 
                            data-slug="{{ $country->slug }}" 
                            @if(
                                isset($traveller['birth_country']) && $country->slug == $traveller['birth_country']
                                ) selected @endif
                            >
                            {{ $country->name }} - {{ $country->code }}
                        </option>
                    @endforeach
                </select>
            </div>
            */ @endphp

        </div>

    @endforeach

@else

    No travellers found.

@endif

<style>
    .btn-remove-traveller {
        display: none;
    }
</style>

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

    $(document).ready(function() {

        // Show/hide passport fields when skip checkbox is checked
        $('input[name="travellers[skip_pass][]"]').change(function() {
            var index = $(this).attr('id').split('-').pop();
            if($(this).is(':checked')) {
                $('.traveller-pass-' + index).hide();
            } else {
                $('.traveller-pass-' + index).show();
                $('.select2-container').css('display', 'block'); // Fix Select2 bug
            }
        });

        // Check if skip checkbox is checked on load
        $('input[name="travellers[skip_pass][]"]').each(function() {
            var index = $(this).attr('id').split('-').pop();
            if($(this).is(':checked')) {
                $('.traveller-pass-' + index).hide();
            }
        });


    });

    $('#multiStepForm').on('submit', function () {
        $(this).find('input[type="checkbox"]').each(function () {
            if (!$(this).is(':checked')) {
                // Add a hidden input with the same name and value "false"
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', $(this).attr('name'))
                    .val('false')
                    .insertAfter($(this));
            }
        });
    });

</script>