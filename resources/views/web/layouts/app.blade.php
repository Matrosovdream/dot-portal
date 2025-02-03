<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    @include('web.includes.metas')

    <!-- Scripts -->
    @foreach([
        '/css/user/bootstrap.min.css',
        '/css/user/fontawesome.css',
        '/css/user/animate.css',
        '/css/user/swiper.min.css',
        '/css/user/odometer.css',
        '/css/user/nice-select.css',
        '/css/user/jquery-ui.min.css',
        '/css/user/magnific-popup.css',
        '/css/user/main.css',
        'css/user/extra.css'
    ] as $asset)
        <link rel="stylesheet" href="{{ asset($asset) }}">
    @endforeach    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js" type="module"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" type="module"></script>  

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles_top')
    @stack('scripts_top')

</head>

<body class="app">

    <!-- backtotop - start -->
    <div class="xb-backtotop">
        <a href="#" class="scroll">
            <i class="far fa-arrow-up"></i>
        </a>
    </div>
    <!-- backtotop - end -->

    <!-- preloader start -->
    <div id="xb-loadding">
        <div class="loader">
            <div class="plane">
                <img class="plane-img" src="{{ asset('user/assets/img/icon/plane.gif') }}" alt="">
            </div>
            <div class="earth-wrapper">
                <div class="earth"></div>
            </div>
        </div>
    </div>
    <!-- preloader end -->

    <div class="body_wrap">

        @include('web.includes.header')

        <div class="body-overlay"></div>

        <main>

            @yield('content')

        </main>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

    <!-- Scripts -->
    @foreach([
        '/js/user/jquery-3.7.1.min.js',
        '/js/user/bootstrap.bundle.min.js',
        '/js/user/swiper.min.js',
        //'resources/js/user/wow.min.js',
        '/js/user/appear.js',
        '/js/user/odometer.min.js',
        '/js/user/jquery.nice-select.min.js',
        '/js/user/imagesloaded.pkgd.min.js',
        '/js/user/isotope.pkgd.min.js',
        '/js/user/jquery.magnific-popup.min.js',
        '/js/user/jquery-ui.min.js',
        '/js/user/parallax-scroll.js',
        '/js/user/main.js'
    ] as $asset)
        <script src="{{ asset($asset) }}"></script>
    @endforeach

    @include('web.includes.footer')

    @stack('styles_bottom')
    @stack('scripts_bottom')

</body>

</html>