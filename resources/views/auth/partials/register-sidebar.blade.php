
<div class="d-flex flex-column flex-lg-row-auto w-lg-350px w-xl-500px">
    <div 
        class="d-flex flex-column position-lg-fixed top-0 bottom-0 w-lg-350px w-xl-500px scroll-y bgi-size-cover bgi-position-center" 
        style="background-image: url({{ asset('assets/admin/media/misc/auth-bg.png') }})"
        >

        <div class="d-flex flex-center py-10 py-lg-20 mt-lg-20">

            <a href="index.html">
                <img alt="Logo" src="{{ asset('assets/admin/media/logos/custom-1.png') }}" class="h-70px" />
            </a>

        </div>

        <div class="d-flex flex-row-fluid justify-content-center p-10">
            <div class="stepper-nav">

                @foreach( $steps as $step => $details)

                    <div class="stepper-item {{ $details['active'] == true ? 'current' : '' }}" data-kt-stepper-element="nav">

                        <div class="stepper-wrapper">

                            <div class="stepper-icon rounded-3">
                                <i class="ki-duotone ki-check fs-2 stepper-check"></i>
                                <span class="stepper-number">{{ $details['number'] }}</span>
                            </div>              

                            <div class="stepper-label">
                                <h3 class="stepper-title fs-2">{{ $details['title'] }}</h3>
                                <div class="stepper-desc fw-normal">{{ $details['description'] }}</div>
                            </div>  

                        </div>  

                        @if( !$loop->last )
                            <div class="stepper-line h-40px"></div>
                        @endif

                    </div>
                @endforeach

            </div>
 
        </div>

        <div class="d-flex flex-center flex-wrap px-5 py-10">

            <div class="d-flex fw-normal">
                <a href="https://keenthemes.com" class="text-success px-5" target="_blank">Terms</a>
                <a href="https://devs.keenthemes.com" class="text-success px-5" target="_blank">Plans</a>
                <a href="https://1.envato.market/EA4JP" class="text-success px-5" target="_blank">Contact Us</a>
            </div>

        </div>

    </div>
</div>
