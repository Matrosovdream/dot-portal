@extends('web.layouts.app')

@section('content')

<main>

    @include('web.includes.direction-search-form')

    <!-- brand start -->
    <!--
    <section class="brand pt-110 pb-90">
        <div class="container">
            <h2 class="brand-title text-center mb-50">
                <span><span>We're proud to work with our preferred partners</span></span>
            </h2>
            <div class="xb-swiper-sliders brand-slider">
                <div class="xb-carousel-inner">
                    <div class="xb-swiper-container swiper-container">
                        <div class="xb-swiper-wrapper swiper-wrapper">
                            <div class="swiper-slide xb-swiper-slide">
                                <a href="#!"><img src="{{ asset('user/assets/img/brand/img_01.png') }}" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    -->
    <!-- brand end -->

    <!-- visa type start -->
    <section class="visa type pb-135 mt-50">
        <div class="container">
            <div class="service-wrap pos-rel">
                <div class="service-img-wrap">
                    <div class="service-bg" data-background="assets/img/bg/service_bg.svg') }}"></div>
                    <div class="service-img wow skewIn" data-wow-delay="100ms"
                        data-background="{{ asset('user/assets/img/service/img_01.jpg') }}"></div>
                </div>
                <div class="sec-title wow skewIn pt-120">
                    <h2 class="mb-60">Visa types and eligibility <br> <span>assessment</span></h2>
                </div>
                <div class="row justify-content-md-center mt-none-30">
                    <div class="col-lg-4 col-md-6 mt-30">
                        <div class="xb-service">
                            <div class="xb-item--inner">
                                <div class="xb-item--icon mb-50">
                                    <img src="{{ asset('user/assets/img/icon/sv_01.svg') }}" alt="">
                                </div>
                                <div class="xb-item--holder">
                                    <h3 class="xb-item--title mb-20"><a href="visa-single.html">Tourist Visa</a>
                                    </h3>
                                    <div class="xb-item--description">
                                        Visit new places to discover with a Tourist Visa. We deliver your documents
                                        ...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mt-30">
                        <div class="xb-service">
                            <div class="xb-item--inner">
                                <div class="xb-item--icon color2 mb-50">
                                    <img src="{{ asset('user/assets/img/icon/sv_02.svg') }}" alt="">
                                </div>
                                <div class="xb-item--holder">
                                    <h3 class="xb-item--title mb-20"><a href="visa-single.html">Commercial
                                            Visa</a></h3>
                                    <div class="xb-item--description">
                                        Developing your trade, setting up new sales channels Your visa is ready...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-30"></div>
                    <div class="col-lg-4 col-md-6 mt-30">
                        <div class="xb-service">
                            <div class="xb-item--inner">
                                <div class="xb-item--icon color3 mb-50">
                                    <img src="{{ asset('user/assets/img/icon/sv_03.svg') }}" alt="">
                                </div>
                                <div class="xb-item--holder">
                                    <h3 class="xb-item--title mb-20"><a href="visa-single.html">Student Visa</a>
                                    </h3>
                                    <div class="xb-item--description">
                                        Embarking on a journey of higher education in a foreign country opens doors
                                        to...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mt-30">
                        <div class="xb-service">
                            <div class="xb-item--inner">
                                <div class="xb-item--icon color4 mb-50">
                                    <img src="{{ asset('user/assets/img/icon/sv_04.svg') }}" alt="">
                                </div>
                                <div class="xb-item--holder">
                                    <h3 class="xb-item--title mb-20"><a href="visa-single.html">Residence
                                            Visa</a></h3>
                                    <div class="xb-item--description">
                                        Expert Guidance for a Seamless Immigration Journey Expert Guidance...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mt-30">
                        <div class="xb-service">
                            <div class="xb-item--inner">
                                <div class="xb-item--icon color5 mb-50">
                                    <img src="{{ asset('user/assets/img/icon/sv_05.svg') }}" alt="">
                                </div>
                                <div class="xb-item--holder">
                                    <h3 class="xb-item--title mb-20"><a href="visa-single.html">Working Visa</a>
                                    </h3>
                                    <div class="xb-item--description">
                                        Get your Visa now for new business and earning opportunities. We deliver
                                        your...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- visa type end -->

    <!-- about start -->
    <section class="about pos-rel pb-130">
        <div class="container">
            <div class="sec-title mb-55">
                <h2 class="mb-30 wow skewIn">Dependable and Trustworthy Visa & <br>
                    <span>Immigration Guidance</span>
                </h2>
                <p>Our team of seasoned professionals understands the <br> complexities of immigration laws and visa
                    procedures.</p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-10">
                    <div class="about__content">
                        <ul class="about-list ul_li list-unstyled">
                            <li>
                                <div class="xb-item--inner">
                                    <div class="xb-item--number">1</div>
                                    <div class="xb-item--holder">
                                        <h3 class="xb-item--title mb-10">Choose your visa type</h3>
                                        <div class="xb-item--description">
                                            Determine the Visa type for your travel purpose.
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="xb-item--inner">
                                    <div class="xb-item--number color-2">2</div>
                                    <div class="xb-item--holder">
                                        <h3 class="xb-item--title mb-10">Contact our branches</h3>
                                        <div class="xb-item--description">
                                            Start your transaction by applying to our branches.
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="xb-item--inner">
                                    <div class="xb-item--number color-3">3</div>
                                    <div class="xb-item--holder">
                                        <h3 class="xb-item--title mb-10">Submit All Your Documents</h3>
                                        <div class="xb-item--description">
                                            Collect all the required documents the process.
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="xb-item--inner">
                                    <div class="xb-item--number color-4">4</div>
                                    <div class="xb-item--holder">
                                        <h3 class="xb-item--title mb-10">Passport delivery</h3>
                                        <div class="xb-item--description">
                                            Receive your visa, which is finalized after application,
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="about__img">
            <img src="{{ asset('user/assets/img/about/about_img.png') }}" alt="">
        </div>
    </section>
    <!-- about end -->

    <!-- counter start -->
    <section class="counter pt-120 pb-120 bg_img" data-background="{{ asset('user/assets/img/bg/counter_bg.jpg') }}">
        <div class="container">
            <div class="sec-title mb-45">
                <h2 class="mb-40 wow skewIn">Discovering Our Biggest Successes: The Stories <br> <span> Behind Our Great
                        Achievements</span>
                </h2>
                <p>Embarking on a journey to reunite families, we recently had the privilege of assisting a <br>
                    couple in securing their spouse's visa. </p>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="xb-counter ul_li">
                        <div class="xb-item--item ul_li">
                            <div class="xb-item--icon">
                                <img src="{{ asset('user/assets/img/icon/c_01.svg') }}" alt="">
                            </div>
                            <div class="xb-item--holder">
                                <h2 class="xb-item--number"><span class="xbo" data-count="20">00</span><span
                                        class="suffix">+</span></h2>
                                <h5 class="xb-item--title">Visa Categories</h5>
                            </div>
                        </div>
                        <div class="xb-item--item style-2 ul_li">
                            <div class="xb-item--icon">
                                <img src="{{ asset('user/assets/img/icon/c_02.svg') }}" alt="">
                            </div>
                            <div class="xb-item--holder">
                                <h2 class="xb-item--number"><span class="xbo" data-count="30">00</span><span
                                        class="suffix">K+</span></h2>
                                <h5 class="xb-item--title">Visa Process</h5>
                            </div>
                        </div>
                        <div class="xb-item--item style-3 ul_li">
                            <div class="xb-item--icon">
                                <img src="{{ asset('user/assets/img/icon/c_03.svg') }}" alt="">
                            </div>
                            <div class="xb-item--holder">
                                <h2 class="xb-item--number"><span class="xbo" data-count="40">00</span><span
                                        class="suffix">K+</span></h2>
                                <h5 class="xb-item--title">Successful Project</h5>
                            </div>
                        </div>
                        <div class="xb-item--item style-4 ul_li">
                            <div class="xb-item--icon">
                                <img src="{{ asset('user/assets/img/icon/c_04.svg') }}" alt="">
                            </div>
                            <div class="xb-item--holder">
                                <h2 class="xb-item--number"><span class="xbo" data-count="180">00</span><span
                                        class="suffix">K+</span></h2>
                                <h5 class="xb-item--title">Pro Consultants</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- counter end -->

    <!--
    <section class="country pt-120 pb-130">
        <div class="container">
            <div class="row mb-30 align-items-center">
                <div class="col-lg-6">
                    <div class="sec-title">
                        <h2 class="mb-20 wow skewIn">Make Your Choice for the <br> <span>Preferred Nation</span>
                        </h2>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="sec-title mb-20">
                        <p>Choosing the ideal destination for immigration is a pivotal decision that can shape the
                            trajectory of your </p>
                    </div>
                </div>
            </div>
            <ul class="xb-country-nav nav nav-tabs ul_li_between mb-65" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="xbc-tab1" data-bs-toggle="tab" data-bs-target="#xbc-tab-pane1"
                        type="button" role="tab" aria-controls="xbc-tab-pane1" aria-selected="true">Europe
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="xbc-tab2" data-bs-toggle="tab" data-bs-target="#xbc-tab-pane2"
                        type="button" role="tab" aria-controls="xbc-tab-pane2" aria-selected="false">North
                        America
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="xbc-tab3" data-bs-toggle="tab" data-bs-target="#xbc-tab-pane3"
                        type="button" role="tab" aria-controls="xbc-tab-pane3" aria-selected="false">Asia
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="xbc-tab4" data-bs-toggle="tab" data-bs-target="#xbc-tab-pane4"
                        type="button" role="tab" aria-controls="xbc-tab-pane4" aria-selected="false">Latin
                        America
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="xbc-tab5" data-bs-toggle="tab" data-bs-target="#xbc-tab-pane5"
                        type="button" role="tab" aria-controls="xbc-tab-pane5" aria-selected="false">Oceania
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="xbc-tab6" data-bs-toggle="tab" data-bs-target="#xbc-tab-pane6"
                        type="button" role="tab" aria-controls="xbc-tab-pane6" aria-selected="false">Africa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="xbc-tab7" data-bs-toggle="tab" data-bs-target="#xbc-tab-pane7"
                        type="button" role="tab" aria-controls="xbc-tab-pane7" aria-selected="false">Antarctica
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane animated fadeInUp show active" id="xbc-tab-pane1" role="tabpanel"
                    aria-labelledby="xbc-tab1" tabindex="0">
                    <div class="xb-country ul_li">
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_01.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Canada</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_02.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Belgium</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_03.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Denmark</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_04.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Australia</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_05.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">France</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_06.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Germany</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Greece</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Hungary</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_09.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Iceland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Ireland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Italy</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_12.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Luxembourg</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane animated fadeInUp" id="xbc-tab-pane2" role="tabpanel" aria-labelledby="xbc-tab2"
                    tabindex="0">
                    <div class="xb-country ul_li">
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_02.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Belgium</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_03.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Denmark</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_09.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Iceland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_04.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Australia</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_05.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">France</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_01.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Canada</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_06.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Germany</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Hungary</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_12.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Luxembourg</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Ireland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Greece</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Italy</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane animated fadeInUp" id="xbc-tab-pane3" role="tabpanel" aria-labelledby="xbc-tab3"
                    tabindex="0">
                    <div class="xb-country ul_li">
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_02.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Belgium</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_06.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Germany</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_03.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Denmark</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_05.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">France</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Greece</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Italy</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Hungary</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_01.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Canada</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Ireland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_04.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Australia</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_12.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Luxembourg</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_09.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Iceland</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane animated fadeInUp" id="xbc-tab-pane4" role="tabpanel" aria-labelledby="xbc-tab4"
                    tabindex="0">
                    <div class="xb-country ul_li">
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_06.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Germany</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_04.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Australia</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_05.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">France</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Greece</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_12.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Luxembourg</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_09.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Iceland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_01.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Canada</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_02.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Belgium</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_03.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Denmark</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Ireland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Hungary</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Italy</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane animated fadeInUp" id="xbc-tab-pane5" role="tabpanel" aria-labelledby="xbc-tab5"
                    tabindex="0">
                    <div class="xb-country ul_li">
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_06.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Germany</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Greece</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Italy</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_09.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Iceland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Ireland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_12.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Luxembourg</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_01.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Canada</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Hungary</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_02.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Belgium</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_03.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Denmark</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_04.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Australia</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_05.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">France</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane animated fadeInUp" id="xbc-tab-pane6" role="tabpanel" aria-labelledby="xbc-tab6"
                    tabindex="0">
                    <div class="xb-country ul_li">
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Hungary</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Ireland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Italy</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_12.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Luxembourg</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_01.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Canada</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_09.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Iceland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_02.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Belgium</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_03.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Denmark</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_04.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Australia</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Greece</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_05.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">France</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_06.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Germany</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane animated fadeInUp" id="xbc-tab-pane7" role="tabpanel" aria-labelledby="xbc-tab7"
                    tabindex="0">
                    <div class="xb-country ul_li">
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_06.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Germany</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Greece</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_08.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Hungary</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_09.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Iceland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_01.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Canada</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Italy</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_12.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Luxembourg</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_02.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Belgium</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_10.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Ireland</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_03.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Denmark</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_05.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">France</h3>
                            </a>
                        </div>
                        <div class="xb-item--item">
                            <a href="country-single.html" class="xb-item--inner ul_li">
                                <div class="xb-item--flag">
                                    <img src="{{ asset('user/assets/img/country/img_04.png') }}" alt="">
                                </div>
                                <h3 class="xb-item--title">Australia</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
-->

    <!-- faq start -->
    <section class="faq pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="xb-faq-content">
                        <div class="sec-title mb-125">
                            <h2 class="mb-30 wow skewIn">Common questions <br> <span> answered</span></h2>
                            <p>At the heart of our commitment to providing <br> exceptional immigration solutions
                                stands our trusted</p>
                        </div>
                        <div class="faq-img">
                            <img src="{{ asset('user/assets/img/faq/faq_img.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="xb-faq">
                        <ul class="accordion_box clearfix">
                            <li class="accordion block active-block">
                                <div class="acc-btn">
                                    What services do you offer?
                                    <span class="arrow"></span>
                                </div>
                                <div class="acc_body current">
                                    <div class="content">
                                        <p>We offer comprehensive immigration and visa consulting services, <br>
                                            including visa application assistance, document preparation,</p>
                                        <ul>
                                            <li><i class="far fa-check"></i>Comprehensive Visa Assistance</li>
                                            <li><i class="far fa-check"></i>Visa Category Expertise</li>
                                            <li><i class="far fa-check"></i>Transparency and Communication</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- faq end -->

    <!-- blog start -->
    <!--
    <section class="blog pb-130">
        <div class="container">
            <div class="blog-wrap">
                <div class="sec-title mb-60 text-center">
                    <h2 class="mb-30 wow skewIn">Cast Your Eyes Upon Our <br> <span> Newest Article</span></h2>
                    <p>Explore the most recent addition to our informative articles</p>
                </div>
                <div class="row justify-content-md-center mt-none-30">

                    @foreach($articles as $article)

                        <div class="col-lg-4 col-md-6 mt-30">
                            <div class="xb-blog">
                                <div class="xb-item--inner">
                                    <div class="xb-item--img">
                                        <img src="{{ asset('user/assets/img/blog/img_01.jpg') }}" alt="">
                                    </div>
                                    <div class="xb-item--holder">
                                        <span class="xb-item--category">Consulting</span>
                                        <ul class="xb-item--meta ul_li mb-20">
                                            <li>
                                                <img src="{{ asset('user/assets/img/icon/calendar.svg') }}" alt="">
                                                {{ $article->created_at->format('d F Y') }}
                                            </li>
                                        </ul>
                                        <h3 class="xb-item--title border-effect">
                                            <a href="blog-single.html">{{ $article->title }}</a>
                                        </h3>
                                        <a class="xb-item--link" href="{{ route('web.articles.show', $article->id) }}">
                                            Read the article
                                            <span>
                                                <img
                                                    src="{{ asset('user/assets/img/icon/right_arrow.svg') }}"
                                                    alt=""></span>
                                        </a>
                                    </div>
                                    <a class="xb-overlay xb-overlay-link" href="{{ route('web.articles.show', $article->id) }}"></a>
                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>
                <div class="xb-blog-bg" data-bg-color="#EDF3F5" data-background="assets/img/bg/blog_bg.svg') }}"></div>
            </div>
        </div>
    </section>
    
    <!-- blog end -->

    <!-- contact start -->
    <!--
    <section class="contact contact-pt gray-bg">
        <div class="container">
            <div class="xb-contact pos-rel">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="xb-item--inner">
                            <div class="xb-item--holder mb-25">
                                <span><img src="{{ asset('user/assets/img/icon/n_pad.svg') }}" alt="">Contact Us</span>
                                <h3>Do you have questions or went more <br> information?</h3>
                            </div>
                            <form class="xb-item--form contact-from" action="#!">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="xb-item--field">
                                            <span><img src="{{ asset('user/assets/img/icon/c_web.svg') }}"
                                                    alt=""></span>
                                            <input type="text" placeholder="Goladria Gomez">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="xb-item--field">
                                            <span><img src="{{ asset('user/assets/img/icon/c_mail.svg') }}"
                                                    alt=""></span>
                                            <input type="text" placeholder="e.visa@services.com">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="xb-item--field">
                                            <span><img src="{{ asset('user/assets/img/icon/c_select.svg') }}"
                                                    alt=""></span>
                                            <select name="select" class="nice-select">
                                                <option value="1">Student Visa</option>
                                                <option value="2">Tourist Visa</option>
                                                <option value="3">Commercial Visa</option>
                                                <option value="4">Residence Visa</option>
                                                <option value="4">Working Visa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="xb-item--field">
                                            <span><img src="{{ asset('user/assets/img/icon/c_call.svg') }}"
                                                    alt=""></span>
                                            <input type="text" placeholder="+888 -8867 3333">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="xb-item--field">
                                            <span><img src="{{ asset('user/assets/img/icon/c_message.svg') }}"
                                                    alt=""></span>
                                            <textarea name="message" id="message" cols="30" rows="10"
                                                placeholder="Write Your Message..."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="thm-btn" type="submit">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="google-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14602.254272231177!2d90.3654215!3d23.7985508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1592852423971!5m2!1sen!2sbd"></iframe>
                </div>
            </div>
        </div>
    </section>
    -->
    <!-- contact end -->
    

</main>

@endsection