@extends('layouts.public')

@section('content')
    <!-- Hero Section Starts -->
    <section class="hero_area pt-120 pb-16 position-relative">
        <div class="container z-1">
            <div class="row justify-content-center mt-8 mt-sm-13 mt-md-0">
                <div class="col-xl-9">
                    <div class="hero_area__content pt-17 pt-sm-20 pt-lg-0 text-center">
                        <span class="fs-five py-2 px-3 px-sm-5 mb-4 wow fadeInUp">Trust and Security in Web3 Crypto
                            Exchange</span>
                        <h1 class="display-three mb-5 mb-md-6 wow fadeInUp">Trust and Security in Web3 Crypto Exchange
                        </h1>
                        <p class="mb-8 mb-md-10 wow fadeInUp">Our comprehensive cybersecurity platform, driven by
                            artificial
                            intelligence, not only <br> safeguards your organization but also educates your workforce.
                        </p>
                        <div
                            class="d-flex align-items-center justify-content-center flex-wrap gap-4 gap-md-6 mb-10 mb-md-13 wow fadeInUp">
                            <a class="hero_area__content-btnone cmn-btn px-6 px-md-8 py-3 d-center gap-3"
                                href="{{ route('auth.register') }}">Get Started
                                <i class="ti ti-chevron-right fs-five px-1 bg4-color p6-color rounded-3 fw-bolder"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="hero_area__thumb wow fadeInUp position-relative">
                        <img class="rounded-5" src="/theme/images/hero-banner.png" alt="Hero Banner">
                        <img class="hero_area__thumb-style leftright-animation position-absolute"
                            src="/theme/images/round-image-for-home-page.png" alt="Image">
                    </div>
                </div>
            </div>
        </div>
        <div class="hero_area__shape">
            <img class="position-absolute rotated_animattwo" src="/theme/images/hero-shape.png" alt="Shape">
        </div>
        <div class="hero_area__lishtone">
            <img class="position-absolute opacity-75" src="/theme/images/lightone.png" alt="light">
        </div>
        <div class="hero_area__lishttwo">
            <img class="position-absolute opacity-75" src="/theme/images/lighttwo.png" alt="light">
        </div>
    </section>
    <!-- Hero Section Ends -->

    <!-- Brand Slider Starts -->
    <div class="brand_slider overflow-hidden pb-15 bg9-color">
        <div class="container-fluid pt-120">
            <div class="row">
                <div class="hero_area__sliderarea px-0">
                    <span class="hero_area__backgroundrote d-block"></span>
                    <div class="hero_area__sliders bg4-color">
                        <div class="swiper brad-carousel overflow-visible d-center">
                            <div class="brandslider swiper-wrapper d-flex align-items-center mt-5 mt-md-0">
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/ripple.png" alt="Brand">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/coinbase.png" alt="Brand">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/binance.png" alt="Brand">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/bitfinex.png" alt="Brand">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/coinbase.png" alt="Brand">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/steemit.png" alt="Brand">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/bitfinex.png" alt="Brand">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="items-wrapper">
                                        <img src="/theme/images/icon/coinbase.png" alt="Brand">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Brand Slider Ends -->

    <!-- Explore Coinx Web3 Products Starts -->
    <section class="web3_product how_join bg9-color pt-120 pb-120">
        <div class="container">
            <div class="row gy-5 gy-md-6">
                <div class="how_join__title text-center mb-10 mb-md-15">
                    <h2 class="wow fadeInUp">Explore DcentWeb3 Products</h2>
                </div>
                <div class="col-sm-6 col-lg-4 col-xxl-3">
                    <div
                        class="web3_product__item how_join__item py-7 py-md-10 px-6 px-md-8 rounded-3 br2 position-relative wow fadeInUp">
                        <div
                            class="how_join__item-thumb mb-4 mb-md-5 text-center p-6 bg1-color rounded-item d-inline-block">
                            <img src="/theme/images/icon/subscription.png" alt="Icons">
                        </div>
                        <h4 class="mb-4 mb-md-5">Staking</h4>
                        <p class="mb-6 mb-md-8">Unlock passive profits with just one click!</p>
                        <div class="web3_product__item-btn">
                            <a class="cmn-btn third-alt px-3 py-2 rounded-5" href="{{ route('auth.register') }}"><i
                                    class="ti ti-arrow-up-right fs-four"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 col-xxl-3">
                    <div
                        class="web3_product__item how_join__item py-7 py-md-10 px-6 px-md-8 rounded-3 br2 position-relative wow fadeInUp">
                        <div
                            class="how_join__item-thumb mb-4 mb-md-5 text-center p-6 bg1-color rounded-item d-inline-block">
                            <img src="/theme/images/icon/snapshot.png" alt="Icons">
                        </div>
                        <h4 class="mb-4 mb-md-5">IDO</h4>
                        <p class="mb-6 mb-md-8">Be the first to enjoy exclusive token discounts</p>
                        <div class="web3_product__item-btn">
                            <a class="cmn-btn third-alt px-3 py-2 rounded-5" href="{{ route('auth.register') }}">
                                <i class="ti ti-arrow-up-right fs-four"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 col-xxl-3">
                    <div
                        class="web3_product__item how_join__item py-7 py-md-10 px-6 px-md-8 rounded-3 br2 position-relative wow fadeInUp">
                        <div
                            class="how_join__item-thumb mb-4 mb-md-5 text-center p-6 bg1-color rounded-item d-inline-block">
                            <img src="/theme/images/icon/lottery.png" alt="Icons">
                        </div>
                        <h4 class="mb-4 mb-md-5">Lottery</h4>
                        <p class="mb-6 mb-md-8">Participate in our exciting lottery to win big prizes!</p>
                        <div class="web3_product__item-btn">
                            <a class="cmn-btn third-alt px-3 py-2 rounded-5" href="{{ route('auth.register') }}">
                                <i class="ti ti-arrow-up-right fs-four"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 col-xxl-3">
                    <div
                        class="web3_product__item how_join__item py-7 py-md-10 px-6 px-md-8 rounded-3 br2 position-relative wow fadeInUp">
                        <div
                            class="how_join__item-thumb mb-4 mb-md-5 text-center p-6 bg1-color rounded-item d-inline-block">
                            <img src="/theme/images/icon/redemption.png" alt="Icons">
                        </div>
                        <h4 class="mb-4 mb-md-5">Buy Crypto</h4>
                        <p class="mb-6 mb-md-8">Buy Crypto Anytime, Anywhere, Easily.</p>
                        <div class="web3_product__item-btn">
                            <a class="cmn-btn third-alt px-3 py-2 rounded-5" href="{{ route('auth.register') }}">
                                <i class="ti ti-arrow-up-right fs-four"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-center mt-2 mt-md-4">
                        <a href="{{ route('auth.register') }}" class="cmn-btn py-2 px-5 px-md-6 d-inline-flex justify-content-center align-items-center roboto">
                            VIEW MORE <i class="ti ti-chevron-right fs-four"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Explore Coinx Web3 Products Ends -->

    <!-- How to get started Starts -->
    <section class="get_started pt-120 pb-120 bg7-colo">
        <div class="container">
            <div
                class="row justify-content-center justify-content-sm-between align-items-center gy-5 gy-md-6 mb-10 mb-mb-15 pb-8 pb-md-10">
                <div class="col-lg-5 col-lg-5 col-xxl-4">
                    <h2 class="text-center text-sm-start wow fadeInUp">How to get started</h2>
                </div>
            </div>
            <div class="row bg1-color rounded-4">
                <div class="col-md-6 col-xl-4 col-xxl-3 p-xxl-0">
                    <div class="get_started__item px-5 py-13 text-center position-relative wow fadeInUp">
                        <span class="get_started__item-icn py-3 px-4 rounded-5 bg1-color mb-5 mb-md-6">
                            <i class="ti ti-user fs-four"></i>
                        </span>
                        <h4 class="p1-color mb-5 mb-md-6">Create account</h4>
                        <span class="roboto mb-8 mb-mb-10 d-block">
                            Create your {{ $org_name }} account in just a few seconds and start your crypto journey today!
                        </span>
                        <a href="{{ route('auth.register') }}" class="cmn-btn third-alt py-3 px-5 px-md-6">Register now</a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 col-xxl-3 p-xxl-0">
                    <div class="get_started__item px-5 py-13 text-center position-relative wow fadeInUp">
                        <span class="get_started__item-icn py-3 px-4 rounded-5 bg1-color mb-5 mb-md-6">
                            <i class="ti ti-shield-filled fs-four"></i>
                        </span>
                        <h4 class="p1-color mb-5 mb-md-6">Verify your identity</h4>
                        <span class="roboto mb-8 mb-mb-10 d-block">
                            Spend less than five minutes completing the verification process.
                        </span>
                        <a href="{{ route('core.client.profile-update') }}" class="cmn-btn third-alt py-3 px-5 px-md-6">Verify now</a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 col-xxl-3 p-xxl-0">
                    <div class="get_started__item px-5 py-13 text-center position-relative wow fadeInUp">
                        <span class="get_started__item-icn py-3 px-4 rounded-5 bg1-color mb-5 mb-md-6">
                            <i class="ti ti-home-dollar fs-four"></i>
                        </span>
                        <h4 class="p1-color mb-5 mb-md-6">Deposit and buy crypto</h4>
                        <span class="roboto mb-8 mb-mb-10 d-block">
                            Deposit funds into your account open up a new array of possibilities.
                        </span>
                        <a href="{{ route('core.client.deposit.create') }}" class="cmn-btn third-alt py-3 px-5 px-md-6">Deposit now</a>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 col-xxl-3 p-xxl-0">
                    <div class="get_started__item px-5 py-13 text-center position-relative wow fadeInUp">
                        <span class="get_started__item-icn py-3 px-4 rounded-5 bg1-color mb-5 mb-md-6">
                            <i class="ti ti-user fs-four"></i>
                        </span>
                        <h4 class="p1-color mb-5 mb-md-6">Start your journey</h4>
                        <span class="roboto mb-8 mb-mb-10 d-block">
                            Start your crypto journey today and explore the exciting world of digital assets with {{ $org_name }}!
                        </span>
                        <a href="{{ route('auth.register') }}" class="cmn-btn third-alt py-3 px-5 px-md-6">Register now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- How to get started Ends -->

    <!-- Trust and Security Starts -->
    <section class="trust_security pt-120 pb-120">
        <div class="container">
            <div class="row align-items-end justify-content-between gy-5 gy-md-6 mb-10 mb-md-15">
                <div class="col-lg-7 col-xxl-6">
                    <div class="trust_security__title wow fadeInUp">
                        <h2>Trust and Security in Web3</h2>
                        <h2 class="trust_security__title-tstyle">Crypto Exchange</h2>
                    </div>
                </div>
            </div>
            <div class="row gy-5 gy-md-6 justify-content-center">
                <div class="col-lg-8">
                    <div class="row gy-5 gy-md-6 justify-content-center">
                        <div class="col-12">
                            <div class="position-relative wow fadeInUp">
                                <img src="/theme/images/support227.png" alt="Image">
                                <div
                                    class="d-flex align-items-center justify-content-between gap-3  position-absolute bottom-0 start-0 ps-5 ps-md-6 pb-8 pb-md-10 pe-3 pe-md-4 w-100 flex-wrap">
                                    <a href="javascript:void(0)">
                                        <h3>24/7 Support</h3>
                                    </a>
                                    <p>
                                        Count on us for round-the-clock support, ensuring help whenever you need it.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative wow fadeInUp">
                                <img src="/theme/images/trade.png" alt="Image">
                                <a href="javascript:void(0)">
                                    <h3 class="d-flex align-items-center justify-content-between gap-3  position-absolute top-0 start-0 ps-5 ps-md-6 pt-8 pt-md-10 pe-3 pe-md-4 w-100">
                                        Trade Algorithm
                                    </h3>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative wow fadeInUp">
                                <img src="/theme/images/trustedsecure.png" alt="Image">
                                <a href="javascript:void(0)">
                                    <h3 class="d-flex align-items-center justify-content-between gap-3  position-absolute top-0 start-0 ps-5 ps-md-6 pt-8 pt-md-10 pe-3 pe-md-4 w-100">
                                        Trusted & Secure
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row gy-5 gy-md-6 justify-content-center">
                        <div class="col-md-6 col-lg-12">
                            <div class="position-relative wow fadeInUp">
                                <img src="/theme/images/cardtocrypto.png" alt="Image">
                                <a href="javascript:void(0)">
                                    <h3
                                        class="position-absolute top-0 start-0 ps-5 ps-md-6 pt-8 pt-md-10 pe-3 pe-md-4 w-100">
                                        Card-to-crypto purchases</h3>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-12">
                            <div class="position-relative wow fadeInUp">
                                <img src="/theme/images/automatically.png" alt="Image">
                                <a href="javascript:void(0)">
                                    <h3
                                        class="position-absolute bottom-0 start-0 ps-5 ps-md-6 pb-8 pb-md-10 pe-3 pe-md-4 w-100">
                                        Automatically</h3>
                                </a>
                                <a href="javascript:void(0)"
                                    class="trust_security__button cmn-btn px-3 py-2 position-absolute end-0 top-0 rounded-5 mt-5 mt-md-6 me-5 me-md-6"><i
                                        class="ti ti-arrow-up-right fs-four"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Trust and Security Ends -->

    <!-- Your one-step shop for crypto trading starts -->
    <section class="one_stepshop bg4-color pt-120 pb-120">
        <div class="container">
            <div class="row gy-5 gy-md-6 align-items-end justify-content-between mb-10 mb-md-15">
                <div class="col-md-7 col-lg-6">
                    <h2 class="wow fadeInUp">Your one-step shop for crypto trading</h2>
                </div>
            </div>
            <div class="row gy-5 gy-md-6 justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="one_stepshop__item br2 rounded-4 py-8 py-md-10 px-6 px-md-8 text-center wow fadeInUp">
                        <img class="mb-4 mb-md-5" src="/theme/images/icon/radeallassets.png" alt="Icon">
                        <h4 class="mb-4 mb-md-5">Trade all the trending assets</h4>
                        <p class="mb-6 mb-md-8">Discover over 400 cryptocurrencies including all the trending new
                            listings.</p>
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <span class="fs-four">400+</span>
                            <span class="roboto">cryptocurrencies</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="one_stepshop__item br2 rounded-4 py-8 py-md-10 px-6 px-md-7 text-center wow fadeInUp">
                        <img class="mb-4 mb-md-5" src="/theme/images/icon/hedgepoloniex.png" alt="Icon">
                        <h4 class="mb-4 mb-md-5">Hedge with Poloniex Futures</h4>
                        <p class="mb-6 mb-md-8">Discover over 400 cryptocurrencies including all the trending new
                            listings.</p>
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <span class="fs-four">100x</span>
                            <span class="roboto">Max leverage</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="one_stepshop__item br2 rounded-4 py-8 py-md-10 px-6 px-md-8 text-center wow fadeInUp">
                        <img class="mb-4 mb-md-5" src="/theme/images/icon/crossmargin.png" alt="Icon">
                        <h4 class="mb-4 mb-md-5">Cross Margin Trading</h4>
                        <p class="mb-6 mb-md-8">Discover over 400 cryptocurrencies including all the trending new
                            listings.</p>
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <span class="roboto">cryptocurrencies</span>
                            <span class="fs-four">1%</span>
                            <span class="roboto">cryptocurrencies</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Your one-step shop for crypto trading ends -->

    <!-- Ready to Exchange Starts -->
    <section class="ready_exhange pt-120 pb-120 bg4-color position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-7 col-xxl-6">
                    <div class="ready_exhange__content text-center">
                        <h2 class="mb-5 mb-md-6 wow fadeInUp">Ready to Exchange?</h2>
                        <p class="mb-8 mb-md-10 wow fadeInUp">Unlock your {{ $org_name }} account now to trade crypto seamlessly,
                            without any
                            fees for buying, selling, or exchanging. Get started today!</p>
                        <div
                            class="ready_exhange__changenow d-flex align-items-center justify-content-center gap-4 gap-sm-8 gap-lg-10">
                            <a href="{{ route('core.client.swap.create') }}" class="cmn-btn py-3 px-5 px-6 wow fadeInUp">
                                Exchange Now
                            </a>
                            <div class="ready_exhange__changenow-brand wow fadeInUp">
                                <a href="javascript:void(0)">
                                    <i class="ti ti-brand-google-play p1-color fs-three"></i>
                                </a>
                                <a href="javascript:void(0)">
                                    <i class="ti ti-brand-apple p1-color fs-three"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Ready to Exchange Ends -->

    <!-- Rewards FAQs Starts -->
    <section class="rewards_faq pt-120 pb-120 position-relative">
        <div class="container">
            <div class="row align-items-center justify-content-center gy-8">
                <div class="col-xl-7">
                    <div class="rewards_faq__content">
                        <h2 class="mb-5 mb-md-6 wow fadeInUp">FAQs</h2>
                        <p class="roboto mb-8 mb-md-10 wow fadeInUp">
                            Explore our FAQs for fast, informative answers to frequently asked questions and common concerns.
                        </p>
                        <div class="accordion-section">
                            @foreach($faqs as $faq)
                            <div class="accordion-single accsingle mb-5 mb-md-6 wow fadeInUp">
                                <h5 class="header-area d-flex align-items-center justify-content-between">
                                    <button
                                        class="accordion-btn d-flex align-items-start position-relative w-100 fs-five fw-bolder text-start"
                                        type="button">
                                        {{ $faq->question }}
                                    </button>

                                </h5>
                                <div class="content-area">
                                    <div class="content-body">
                                            <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2 wow fadeInUp">
                            <span>Can't see your question listed above?</span>
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-1 p6-color astyle">Chat with our agents live <i
                                    class="ti ti-arrow-narrow-right fs-four mt-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="rewards_faq__thumb">
                        <img src="/theme/images/faq-thumb.png" class="max-un leftright-animation" alt="Images">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Rewards FAQs Ends -->

@endsection
