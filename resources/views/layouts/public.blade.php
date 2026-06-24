<!doctype html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if(strlen((string)$g_analytics_id))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{$g_analytics_id}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', '{{$g_analytics_id}}');
        </script>
    @endif

    <!-- required meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- #keywords -->
    <meta name="keywords" content="{{$org_tagline}}">
    <!-- #description -->
    <meta name="description" content="{{$org_description}}">
    <!-- #title -->
    <title>{{ ($pageTitle ?? $org_tagline).' - '.$org_name }}</title>
    <!-- #favicon -->
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <!-- ==== css dependencies start ==== -->
    <link rel="stylesheet" href="/theme/css/style.min.css">
</head>

<body>
    <!-- Scroll To Top Start-->
    <button class="scrollToTop d-none d-md-flex d-center" aria-label="scroll Bar Button">
        <i class="ti ti-chevron-up fs-four p6-color"></i>
    </button>
    <!-- Scroll To Top End -->
    <!-- start preloader -->
    <div id="preloader" class="pre-item d-center">
        <div class="loaderall"></div>
    </div>
    <!-- end preloader -->

    <!-- header-section start -->
    <div class="navbar_top bg2-color py-4 d-none d-lg-block">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-5 col-xxl-5">
                    <div class="navbar_top__left d-flex align-items-center gap-2 gap-xl-6">
                        <div class="navbar_top__location d-flex align-items-center gap-1 gap-xl-3">
                            <i class="ti ti-map-pin-filled fs-four p7-color"></i>
                            <span class="roboto p7-color">{{ $contact_address }}</span>
                        </div>
                        <span class="v-line mb-9"></span>
                        <a href="mailto:{{ $contact_email }}"
                            class="navbar_top__email roboto p7-color d-flex align-items-center gap-3">
                            <i class="ti ti-mail-opened-filled fs-four"></i>
                            {{ $contact_email }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 col-xxl-6">
                    <div class="navbar_top__right d-flex align-items-center justify-content-end gap-2 gap-xl-6">
                        <div class="navbar_top__call d-flex align-items-center gap-3">
                            <span class="bg6-color py-2 px-3 rounded-item">
                                <i class="ti ti-phone-call fs-four p7-color "></i>
                            </span>
                            <div>
                                <span class="p7-color fw-bolder d-block">Contact Us:</span>
                                <a href="tel:{{ $contact_phone }}" class="d-block p7-color">{{ $contact_phone }}</a>
                            </div>
                        </div>
                        <span class="v-line mb-9"></span>
                        <div class="navbar_top__social d-flex align-items-center gap-2 gap-xl-3">
                            <span class="p7-color fw-bolder">Follow Us:</span>
                            <div class="navbar_top__social-icon d-flex align-items-center  gap-1 gap-xl-2">
                            @if($contact_facebook)
                                <a href="{{ $contact_facebook }}" class="br3 py-2 px-3 rounded-item d-center">
                                    <i class="ti ti-brand-facebook fs-four p7-color "></i>
                                </a>
                            @endif
                            @if($contact_instagram)
                                <a href="{{ $contact_instagram }}" class="br3 py-2 px-3 rounded-item d-center">
                                    <i class="ti ti-brand-instagram fs-four p7-color "></i>
                                </a>
                            @endif
                            @if($contact_twitter)
                                <a href="{{ $contact_twitter }}" class="br3 py-2 px-3 rounded-item d-center">
                                    <i class="ti ti-brand-twitter fs-four p7-color "></i>
                                </a>
                            @endif
                            @if ($contact_telegram)
                                <a href="{{ $contact_telegram }}" class="br3 py-2 px-3 rounded-item d-center">
                                    <i class="ti ti-brand-telegram fs-four p7-color "></i>
                                </a>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <header class="header-section header-menu w-100 pt-1 pt-lg-0 pb-3 pb-lg-0">
        <div class="navbar_mainhead header-fixed w-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-lg position-relative py-md-3 py-lg-6 workready">
                            <a href="index.html" class="navbar-brand d-flex align-items-center gap-2">
                                <img src="/images/logo.png" class="logo" alt="{{ $org_name }}" style="max-height: 52px;">
                            </a>
                            <div class="collapse navbar-collapse justify-content-between" id="navbar-content">
                                <ul
                                    class="navbar-nav d-flex align-items-lg-center gap-5 gap-lg-1 gap-xl-4 gap-xxl-7 py-2 py-lg-0 ms-2 ms-xl-10 ms-xxl-20 ps-0 ps-xxl-10 align-self-center">
                                    <li>
                                        <a href="/" class="fs-ten">Home</a>
                                    </li>
                                    <li class="dropdown show-dropdown">
                                        <button type="button" aria-label="Navbar Dropdown Button" class="dropdown-toggle dropdown-nav d-flex align-items-center fs-ten">
                                            Buy Crypto <i class="ti ti-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item fs-ten" href="https://www.moonpay.com/buy" target="_blank"><img src="/images/icons/moonpay.png" width="14"/> MoonPay</a></li>
                                            <li><a class="dropdown-item fs-ten" href="https://global.transak.com/" target="_blank"><img src="/images/icons/transak.jpeg" width="14"/> Transak</a></li>
                                            <li><a class="dropdown-item fs-ten" href="https://ramp.network/buy/" target="_blank"><img src="/images/icons/ramp.jpeg" width="14"/> Ramp</a></li>
                                            <li><a class="dropdown-item fs-ten" href="https://www.simplex.com/account/buy" target="_blank"><img src="/images/icons/simplex.png" width="14"/> Simplex</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="{{ route('cms.staking-pools') }}" class="fs-ten">Staking Pools</a>
                                    </li>
                                    <li>
                                        <a href="{{route('cms.post.index')}}" class="fs-ten">Blog</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('auth.login') }}" class="fs-ten">Sign in</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('auth.register') }}" class="fs-ten">Sign up</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="right-area custom-pos position-relative d-flex gap-0 gap-lg-2 align-items-center">
                                <div class="header-section__modalstyle">
                                    <a href="{{ route('cms.connect-wallet') }}" class="cmn-btn px-3 px-sm-5 px-md-6 py-2 py-sm-3 d-flex align-items-center gap-1">
                                        <span class="p7-color fw-semibold d-none d-sm-block">Connect</span> Wallet
                                    </a>
                                </div>

                            </div>

                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                aria-label="Navbar Toggler" data-bs-target="#navbar-content" aria-expanded="true"
                                id="nav-icon3">
                                <span></span><span></span><span></span><span></span>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-section end -->

        <div class="bg2-color pt-4 text-center" style="text-align: center; padding: 1rem 0;">
            @include('_components.google-translate')
        </div>

    @yield('content')

    <!-- Footer Section Starts -->
    <footer class="footer pt-120 bg5-color">
        <div class="container">
            <div class="row gy-8 pb-120 justify-content-center">
                <div class="col-md-8 col-lg-6 col-xxl-5">
                    <div class="footer__decs wow fadeInUp text-center">
                        <a href="/">
                            <h2>{{ $org_name }}</h2>
                        </a>
                        <p class="mt-5 mt-md-6 mb-8 mb-md-10 wow fadeInUp">{{ $org_name }} is your gateway to the
                            world of Web3 trading! Our user-friendly platform empowers you to explore a wide range of
                            popular cryptocurrencies
                        </p>
                        <div class="contact_info__card-social d-flex align-items-center justify-content-center gap-2 gap-md-3 wow fadeInUp">
                            @if($contact_facebook)
                                <a href="{{ $contact_facebook }}">
                                    <i class="ti ti-brand-facebook-filled p4-color fs-four fw-normal p-2"></i>
                                </a>
                            @endif
                            @if($contact_telegram)
                                <a href="{{ $contact_telegram }}">
                                    <i class="ti ti-brand-telegram p4-color fs-four fw-normal p-2"></i>
                                </a>
                            @endif
                            @if($contact_instagram)
                                <a href="{{ $contact_instagram }}">
                                    <i class="ti ti-brand-instagram p4-color fs-four fw-normal p-2"></i>
                                </a>
                            @endif
                            @if($contact_twitter)
                                <a href="{{ $contact_twitter }}">
                                    <i class="ti ti-brand-twitter-filled p4-color fs-four fw-normal p-2"></i>
                                </a>
                            @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid ">
            <div class="row align-items-center justify-content-center py-3 py-sm-4 py-lg-6 bg2-color">
                <div class="col-sm-10 col-xxl-8 order-2 order-sm-1">
                    <div
                        class="footer__copyright text-center d-flex align-items-center justify-content-center justify-content-md-between flex-wrap flex-md-nowrap">
                        <div class="coyp-rightarea">
                            <span class="p4-color roboto text-center text-md-start">
                                Copyright {{ date('Y') }} -
                                <a href="/" class="p4-color">{{ $org_name }}</a>
                                All Rights Reserved
                            </span>
                        </div>


                        <div class="privacy-policay d-flex align-items-center gap-3">
                            <a href="javascript:void(0)" class="p4-color roboto ps-4 ps-sm-6">
                                Privacy Policy</a>
                            <span class="p4-color fs-five">|</span>
                            <a href="javascript:void(0)" class="p4-color roboto">
                                Cookie Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section Ends -->

    @if(!Auth::guest())
        <form id="logout-form" action="{{ route('auth.logout') }}" onsubmit="return false;" method="POST"
            class="d-none hidden">
            @csrf
        </form>
    @endif

    <!-- ==== js dependencies start ==== -->
    <script src="/theme/js/plugins/plugins.js"></script>
    <script src="/theme/js/plugins/plugin-custom.js"></script>
    <script src="/theme/js/main.js"></script>

    <script type="text/javascript">
        window.userCurrency = 'USD';
        window.liveChatKey = '{{$livechat_key}}';
        window.loginUrl = '{{route('auth.login')}}';
    </script>
    <script src="{{asset('/js/app-config.js')}}"></script>

    @if($livechat_service)
        @include('_components.Livechat.'.$livechat_service)
    @endif

</body>

</html>