<!-- footer start -->
<footer class="site-footer gray-bg pt-65">
    <div class="container">
        <div class="row mt-none-30 pb-60">
            <div class="col-lg-3 mt-30 col-md-6 footer__custom-col">
                <div class="footer__widget">
                    <h3 class="widget-title">
                        {{ __('Do you have questions or went more information? Contact us now') }}
                    </h3>
                    <ul class="footer__cta list-unstyled mt-50">
                        <li class="ul_li">
                            <span>
                                <img src="{{ asset('user/assets/img/icon/f_call.svg') }}" alt="">
                            </span>
                            {{ $siteSettings['phone'] }}
                        </li>
                        <li class="ul_li">
                            <span>
                                <img src="{{ asset('user/assets/img/icon/f_mail.svg') }}" alt="">
                            </span>
                            {{ $siteSettings['email'] }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 mt-30 col-md-6 footer__custom-col">
                <div class="footer__widget">
                    <h3 class="widget-title">Explore Link</h3>
                    <ul class="footer__links list-unstyled">
                        <li><a href="#!"><span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                        viewBox="0 0 13 13" fill="none">
                                        <path
                                            d="M6.5 0C6.72067 3.49437 9.5056 6.27934 13 6.5C9.5056 6.72067 6.72067 9.5056 6.5 13C6.27934 9.5056 3.49437 6.72067 0 6.5C3.49437 6.27934 6.27934 3.49437 6.5 0Z"
                                            fill="#B1B4BA" />
                                    </svg></span>
                                    {{ __('About Us') }}
                                </a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 mt-30 col-md-6 footer__custom-col">
                <div class="footer__widget">
                    <h3 class="widget-title">Services</h3>
                    <ul class="footer__links list-unstyled">
                        <li><a href="#!"><span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                        viewBox="0 0 13 13" fill="none">
                                        <path
                                            d="M6.5 0C6.72067 3.49437 9.5056 6.27934 13 6.5C9.5056 6.72067 6.72067 9.5056 6.5 13C6.27934 9.5056 3.49437 6.72067 0 6.5C3.49437 6.27934 6.27934 3.49437 6.5 0Z"
                                            fill="#B1B4BA" />
                                    </svg></span>{{ __('Tourist Visa') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 mt-30 col-md-6 footer__custom-col">
                <div class="footer__widget">
                    <h3 class="widget-title">Our branches</h3>
                    <ul class="footer__links list-unstyled">
                        <li>
                            <a href="#!">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                        viewBox="0 0 13 13" fill="none">
                                        <path
                                            d="M6.5 0C6.72067 3.49437 9.5056 6.27934 13 6.5C9.5056 6.72067 6.72067 9.5056 6.5 13C6.27934 9.5056 3.49437 6.72067 0 6.5C3.49437 6.27934 6.27934 3.49437 6.5 0Z"
                                            fill="#B1B4BA" />
                                    </svg>
                                </span>
                                Canada
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer__copyright ul_li_between">
            <div class="footer__copyright-text mt-15">
                {{ $siteSettings['copyright_text'] }}
            </div>
            <div class="footer__copyright-img mt-20">
                <img src="{{ asset('user/assets/img/icon/card_img.png') }}" alt="">
            </div>
        </div>
    </div>
</footer>
<!-- footer start -->