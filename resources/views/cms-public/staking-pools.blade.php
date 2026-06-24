@extends('layouts.public')

@section('content')
    <!--Common hero Section Starts -->
    <section class="cmn_heros pb-120 pt-120">
        <div class="container">
            <div class="row justify-content-center mt-5 mt-md-8 mt-lg-0">
                <div class="col-xxl-6">
                    <div class="cmn_heros__title text-center pt-15 pt-lg-6">
                        <h1 class="display-three mb-5 mb-md-7 wow fadeInUp">Our Staking Pools</h1>
                        <p class="roboto wow fadeInUp">
                            Earn crypto flexibly with DcentWeb3's staking pools. Stake your crypto assets and earn rewards with our secure and user-friendly platform. 
                            Whether you're a beginner or an experienced staker, our staking pools offer competitive rewards and flexible options to help you grow your crypto holdings. 
                            Start staking today and watch your crypto grow with DcentWeb3!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Common hero Section Ends -->
    
    <!-- Staking Pools Section Starts Starts -->
    <section class="pricing_plan pt-120 pb-120 bg5-color">
        <div class="container">
            <div class="row gy-6">

                @foreach ($packages as $package)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="pricing_plan__cards p-6 p-md-8 rounded-20 br2 position-relative wow fadeInUp">
                            <!--
                            <div class="pricing_plan__cards-icon mb-3">
                                <img src="{{ $package->photo_url }}" alt="Icons">
                            </div>
                            -->
                            <h4 class="mb-5 mb-md-6">{{ $package->name }}</h4>
                            <div class="pricing_plan__cards-price d-flex align-items-center gap-3 mb-5 mb-md-6">
                                <p style="display: block;">starts from...</p>
                                <h1 class="p1-color">{{ $package->minAmount() }}</h1>
                            </div>
                            <div class="pricing_plan__cards-befit mb-5 mb-md-6">
                                <ul class="d-flex flex-column gap-4">
                                    <li class="d-flex align-items-center gap-3">
                                        <span class="bg1-color px-1 rounded-item">
                                            <i class="ti ti-check p1-color"></i>
                                        </span>
                                        <p>{{ $package->getInterestRateStr() }} {{ $package->interest_interval == 1 ? 'daily interest' : 'interest every ' . $package->interest_interval . ' days' }}</p>
                                    </li>
                                    <li class="d-flex align-items-center gap-3">
                                        <span class="bg1-color px-1 rounded-item">
                                            <i class="ti ti-check p1-color"></i>
                                        </span>
                                        <p>Full platform access</p>
                                    </li>
                                    <li class="d-flex align-items-center gap-3">
                                        <span class="bg1-color px-1 rounded-item">
                                            <i class="ti ti-check p1-color"></i>
                                        </span>
                                        <p>Flexible withdrawal options</p>
                                    </li>
                                    <li class="d-flex align-items-center gap-3">
                                        <span class="bg1-color px-1 rounded-item">
                                            <i class="ti ti-check p1-color"></i>
                                        </span>
                                        <p>24/7 customer support</p>
                                    </li>
                                    <li class="d-flex align-items-center gap-3">
                                        <span class="bg1-color px-1 rounded-item">
                                            <i class="ti ti-check p1-color"></i>
                                        </span>
                                        <p>Regular updates & improvements</p>
                                    </li>
                                    <li class="d-flex align-items-center gap-3">
                                        <span class="bg1-color px-1 rounded-item">
                                            <i class="ti ti-check p1-color"></i>
                                        </span>
                                        <p>Premium educational resources</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="pricing_plan__cards-btn">
                                <button type="button" class="rounded-2 py-3 px-6 p1-color br4 w-100">Apply Now</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Pricing Plan Section Starts Ends -->
    <!-- Ready to Exchange Starts -->
    <section class="ready_exhange pt-120 pb-120 bg5-color">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-7 col-xxl-6">
                    <div class="ready_exhange__content text-center">
                        <h2 class="mb-5 mb-md-6 wow fadeInUp">Ready to Exchange?</h2>
                        <p class="mb-8 mb-md-10 wow fadeInUp">Unlock your {{ $org_name }} account now to trade crypto seamlessly, without any
                            fees for buying, selling, or exchanging. Get started today!</p>
                        <div
                            class="ready_exhange__changenow d-flex align-items-center justify-content-center gap-4 gap-sm-8 gap-lg-10 wow fadeInUp">
                            <a href="{{ route('core.client.swap.create') }}" class="cmn-btn py-3 px-5 px-6">
                                Exchange Now
                            </a>
                            <div class="ready_exhange__changenow-brand">
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

@endsection