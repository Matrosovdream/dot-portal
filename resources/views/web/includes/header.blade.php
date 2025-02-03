<!-- header start -->
<header class="site-header header-style-one">
    <div class="header__top-wrap gray-bg">
        <div class="container">
            <div class="header__top ul_li_between">
                <div class="header__top-cta">
                    <img src="{{ asset('user/assets/img/icon/n_pad.svg') }}" alt="">
                    <span>
                        {{ __('Help Desk') }}:</span>
                    {{ $siteSettings['phone'] }}
                </div>
                <ul class="header__top-info ul_li">
                    <li><img src="{{ asset('user/assets/img/icon/time.svg') }}" alt="">
                        {{ $siteSettings['work_time'] }}
                    </li>
                    <li><img src="{{ asset('user/assets/img/icon/location.svg') }}" alt="">
                        {{ $siteSettings['address'] }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="header__wrap stricky">
        <div class="container">
            <div class="header__inner ul_li_between">
                <div class="header__logo">
                    <a href="/">
                        <img src="{{ asset('user/assets/img/logo/logo.svg') }}" alt="">
                    </a>
                </div>
                <div class="main-menu__wrap ul_li navbar navbar-expand-lg">
                    <nav class="main-menu collapse navbar-collapse">
                        <ul class="menu-top">
                            @foreach($menuTop as $menu)
                                <li class="@if(isset($menu['childs'])) menu-item-has-children @endif">
                                    <a href="{{ $menu['url'] }}"><span>{{ $menu['title'] }}</span></a>

                                    @if(isset($menu['childs']))
                                        <ul class="submenu">
                                            @foreach($menu['childs'] as $subMenu)
                                                <li class="menu-item">
                                                    <a href="{{ $subMenu->url }}"><span>{{ $subMenu->title }}</span></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </li>
                            @endforeach

                        </ul>
                    </nav>
                </div>
                <div class="xb-hamburger-menu">
                    <div class="xb-nav-mobile">
                        <div class="xb-nav-mobile-button"><i class="fal fa-bars"></i></div>
                    </div>
                </div>
                <ul class="header__action ul_li">

                    @if(Auth::check())

                        <li style="">
                            <div class="header__language">
                                <ul>
                                    <li>
                                        <a href="#" class="lang-btn">
                                            <div class="flag">
                                                <img src="{{ asset('user/assets/img/icon/c_user.svg') }}" alt="">
                                            </div>
                                            {{ __('Dashboard') }}
                                            <div class="arrow_down">
                                                <img src="{{ asset('user/assets/img/icon/arrow_down.svg') }}" alt="">
                                            </div>
                                        </a>
                                        <ul class="lang_sub_list">
                                            @if ( Auth::user()->isAdmin() || Auth::user()->isManager() )
                                                <li>
                                                    <a href="{{ route('dashboard.home') }}">
                                                        {{ __('Dashboard') }}
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ route('web.account.index') }}">
                                                    {{ __('Account') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('web.account.orders') }}">
                                                    {{ __('Orders') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <span>{{ __('Log out') }}</span>
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li>
                            <div class="header__language">
                                <ul>
                                    <li>
                                        <a href="{{ route('login') }}" class="lang-btn">
                                            <div class="flag">
                                                <img src="{{ asset('user/assets/img/icon/c_user.svg') }}" alt="">
                                            </div>
                                            {{ __('Login') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                    <!--
                    <li>
                        <a class="header__search header-search-btn" href="javascript:void(0);">
                            <img src="{{ asset('user/assets/img/icon/search.svg') }}" alt="">
                            Search
                        </a>
                    </li>
-->
                    <li>
                        <div class="header__language">
                            <ul>
                                <li>
                                    <a href="#!" class="lang-btn">
                                        {{ $activeLanguage->name }}
                                        <div class="arrow_down"><img
                                                src="{{ asset('user/assets/img/icon/arrow_down.svg') }}" alt=""></div>
                                    </a>
                                    <ul class="lang_sub_list">
                                        @foreach($languages as $language)
                                            <li>

                                                <form action="{{ route('web.language.set') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="lang" value="{{ $language->code }}">
                                                    <button type="submit">
                                                        {{ $language->name }}
                                                    </button>
                                                </form>

                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="header__language">
                            <ul>
                                <li>
                                    <a href="#!" class="lang-btn">
                                        <span>{{ $activeCurrency->symbol }}</span>
                                        {{ $activeCurrency->name }}
                                        <div class="arrow_down"><img
                                                src="{{ asset('user/assets/img/icon/arrow_down.svg') }}" alt=""></div>
                                    </a>
                                    <ul class="lang_sub_list">
                                        @foreach($currencies as $currency)
                                            <li>

                                                <form action="{{ route('web.currency.set') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="currency" value="{{ $currency->code }}">
                                                    <button type="submit">
                                                        <span>{{ $currency->symbol }}</span>
                                                        {{ $currency->name }}
                                                    </button>
                                                </form>

                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="xb-header-wrap">
        <div class="xb-header-menu">
            <div class="xb-header-menu-scroll">
                <div class="xb-menu-close xb-hide-xl xb-close"></div>
                <div class="xb-logo-mobile xb-hide-xl">
                    <a href="index.html" rel="home">
                        <img src="{{ asset('user/assets/img/logo/logo.svg') }}" alt="">
                    </a>
                </div>
                <!--
                <div class="xb-header-mobile-search xb-hide-xl">
                    <form role="search" action="#">
                        <input type="text" placeholder="Search..." name="s" class="search-field">
                        <button type="submit" class="search-submit">
                        </button>
                    </form>
                </div>
                -->
                <nav class="xb-header-nav">
                    <ul class="xb-menu-primary clearfix">
                        <!--
                        <li class="menu-item menu-item-has-children">
                            <a href="#"><span>Blog</span></a>
                            <ul class="sub-menu">
                                <li class="menu-item"><a href="blog.html"><span>Blog</span></a></li>
                                <li class="menu-item"><a href="blog-single.html"><span>Blog Details</span></a>
                                </li>
                            </ul>
                        </li>
                        -->

                        @foreach($menuTop as $menu)
                            <li class="menu-item">
                                <a href="{{ $menu['url'] }}"><span>{{ $menu['title'] }}</span></a>

                                @if(isset($menu['childs']))
                                    <ul class="sub-menu">
                                        @foreach($menu['childs'] as $subMenu)
                                            <li class="menu-item">
                                                <a href="{{ $subMenu->url }}"><span>{{ $subMenu->title }}</span></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                            </li>
                        @endforeach

                        @if(Auth::check())

                            <li class="menu-item menu-item-has-children">
                                <a href="#">
                                    <span>{{ __('Dashboard') }}</span>
                                </a>
                                <ul class="sub-menu">

                                    <li class="menu-item">
                                        <a href="{{ route('web.account.index') }}">
                                            {{ __('Account') }}
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('web.account.orders') }}">
                                            {{ __('Orders') }}
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span>{{ __('Log out') }}</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>

                        @else

                            <li class="menu-item">
                                <a href="{{ route('login') }}">
                                    <span>{{ __('Login') }}</span>
                                </a>
                            </li>

                        @endif

                    </ul>
                </nav>
            </div>
        </div>
        <div class="xb-header-menu-backdrop"></div>
    </div>
</header>
<!-- header end -->

<!-- header search start -->
<!--
<div class="header-search-form-wrapper">
    <div class="xb-search-close xb-close"></div>
    <div class="header-search-container">
        <form role="search" class="search-form" action="#">
            <input type="search" class="search-field" placeholder="Search …" value="" name="s">
        </form>
    </div>
</div>
-->