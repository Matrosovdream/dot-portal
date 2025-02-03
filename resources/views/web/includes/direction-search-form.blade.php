<!-- hero start -->
<section class="hero hero__style-one bg_img"
    data-background="{{ asset('user/assets/img/hero/homepage-hero.webp') }}" style="min-height: 600px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-10 col-lg-7">
                <div class="hero__content">
                    <h1 class="wow skewIn fs-1">
                       {{ __('Get your travel visa for') }} <br> <span>{{ __('ANY COUNTRY') }}</span>
                    </h1>

                    <div class="p-4 bg-light rounded shadow w-100">
                        <form method="POST" action="{{ route('web.direction.apply') }}" id="search_direction">

                            @csrf

                            <div class="row g-3">
                                <!-- Where am I from? -->
                                <div class="col-md-5">
                                    <label for="fromCountry" class="form-label">
                                        {{ __('Where am I from?') }}
                                    </label>
                                    <select class="select2 form-control" name="country_from">
                                        <option selected disabled></option>
                                        @foreach($countries as $country)
                                            <option 
                                                value="{{ $country->id }}"
                                                @if( $location['countryCode'] == $country->code ) selected @endif
                                                >
                                                {{ $country->name }} - {{ $country->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Where am I going? -->
                                <div class="col-md-5">
                                    <label for="toCountry" class="form-label">
                                        {{ __('Where am I going?') }}
                                    </label>
                                    <select class="select2 form-control" name="country_to">
                                        <option selected disabled></option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">
                                                {{ $country->name }} - {{ $country->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Get Started Button -->
                                <div class="col-md-2 d-flex align-items-end ">
                                    <button type="submit" class="btn btn-success w-100" style="height: 50px;">
                                        {{ __('Get started!') }}
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
<!-- hero end -->


<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#search_direction').validate({
            rules: {
                country_from: {
                    required: true
                },
                country_to: {
                    required: true
                }
            },
            messages: {
                country_from: {
                    required: "Please select your country of origin"
                },
                country_to: {
                    required: "Please select your destination country"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>

<style>
    /* Simple styling for error messages */
    label.error {
        color: red;
        font-size: 14px;
    }
</style>